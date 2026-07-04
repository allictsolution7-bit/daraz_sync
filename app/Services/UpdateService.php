<?php

namespace App\Services;

use App\Models\License;
use App\Services\LicenseService;
use App\Models\UpdateHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use ZipArchive;
use RuntimeException;
use Throwable;

class UpdateService
{
    protected LicenseService $licenseService;
    protected string $manifestCacheKey = 'thikana:update:manifest';
    protected string $updateDir;
    protected string $tempDir;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
        $this->updateDir = storage_path('app/updates');
        $this->tempDir = $this->updateDir . '/tmp';
        File::ensureDirectoryExists($this->updateDir);
        File::ensureDirectoryExists($this->tempDir);
    }

    /**
     * Get the current installed version.
     */
    public function currentVersion(): string
    {
        return Config::get('app.version', '1.0.0');
    }

    /**
     * Get the release channel we should check.
     */
    public function releaseChannel(): string
    {
        return Config::get('app.release_channel', 'stable');
    }

    /**
     * Fetch the next available update manifest.
     */
    public function fetchAvailableUpdate(bool $force = false): ?array
    {
        $license = License::first();
        if (!$license || !$license->canAccessUpdates()) {
            return null;
        }

        $cacheKey = "{$this->manifestCacheKey}.{$license->license_key}";
        if (!$force && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->post(config('license.update.check_url'), [
                'license_key' => $license->license_key,
                'domain' => $this->resolveDomain($license),
                'current_version' => $this->currentVersion(),
                'channel' => $this->releaseChannel(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Update check failed: ' . $e->getMessage());
            return null;
        }

        if (!$response->successful()) {
            Log::warning('Update check HTTP error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $payload = $response->json();

        if (empty($payload['success']) || empty($payload['manifest'])) {
            Log::info('No updates available', [
                'payload' => $payload,
            ]);
            return null;
        }

        $manifest = $payload['manifest'];
        $signature = $payload['signature'] ?? '';

        if (!$signature || !$this->licenseService->verifySignedPayload($manifest, $signature)) {
            Log::warning('Invalid update manifest signature', [
                'license_key' => $license->license_key,
            ]);
            return null;
        }

        Cache::put($cacheKey, $manifest, now()->addMinutes(15));
        return $manifest;
    }

    /**
     * Fetch summary of the latest release from the mother panel (for marketing display).
     */
    public function fetchLatestReleaseSummary(): ?array
    {
        $cacheKey = $this->manifestCacheKey . '.latest_summary.' . $this->releaseChannel();
        $summaryUrl = config('license.update.summary_url')
            ?? env('LICENSE_UPDATE_SUMMARY_URL', 'http://127.0.0.1:8001/api/updates/latest');

        if (!$summaryUrl) {
            return null;
        }

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($summaryUrl, [
                'channel' => $this->releaseChannel(),
            ]);
        } catch (Throwable $e) {
            Log::info('Failed to fetch latest release summary', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }

        if (!$response->successful()) {
            Log::info('Latest release summary HTTP error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $payload = $response->json();

        if (empty($payload['success']) || empty($payload['release'])) {
            return null;
        }

        $summary = $payload['release'];
        Cache::put($cacheKey, $summary, now()->addMinutes(30));

        return $summary;
    }

    /**
     * Apply an update manifest (download, extract, run commands).
     */
    public function applyUpdate(array $manifest, ?int $userId = null): UpdateHistory
    {
        $license = License::first();
        if (!$license) {
            throw new RuntimeException('License record not found');
        }

        $this->ensureSequenceIntegrity($manifest);

        $history = UpdateHistory::create([
            'license_id' => $license->id,
            'applied_by_user_id' => $userId,
            'version' => $manifest['version'],
            'sequence' => $manifest['sequence'] ?? null,
            'status' => 'pending',
            'notes' => 'Update initiated',
            'manifest' => $manifest,
        ]);

        $details = [
            'downloaded_at' => now()->toISOString(),
        ];

        try {
            $zipPath = $this->downloadRelease($manifest);
            $extractPath = $this->extractArchive($zipPath);
            $this->copyDirectoryContents($extractPath, base_path());
            $details['commands'] = $this->runCommands($manifest['commands'] ?? []);
            $this->updateAppVersion($manifest['version']);
            $history->update([
                'status' => 'success',
                'notes' => 'Update applied successfully',
                'details' => $details,
            ]);
            Cache::forget($this->manifestCacheKey . '.' . $license->license_key);
            $this->reportUpdateStatus($manifest, 'success', $details);
            return $history;
        } catch (Throwable $e) {
            $details['exception'] = $e->getMessage();
            $history->update([
                'status' => 'failed',
                'notes' => $e->getMessage(),
                'details' => $details,
            ]);
            $this->reportUpdateStatus($manifest, 'failed', $details);
            throw $e;
        } finally {
            if (isset($extractPath) && is_dir($extractPath)) {
                File::deleteDirectory($extractPath);
            }
        }
    }

    /**
     * Get the recent update history.
     */
    public function getHistory(int $limit = 10)
    {
        return UpdateHistory::latest()->limit($limit)->get();
    }

    /**
     * Download release archive to local storage.
     */
    protected function downloadRelease(array $manifest): string
    {
        $url = $manifest['download_url'] ?? null;
        if (!$url) {
            throw new RuntimeException('Release download URL is missing');
        }

        $fileName = $manifest['filename'] ?? "{$manifest['version']}.zip";
        $targetPath = $this->updateDir . '/' . $fileName;

        $response = Http::timeout(120)
            ->withOptions(['verify' => true])
            ->sink($targetPath)
            ->get($url);

        if (!$response->successful()) {
            throw new RuntimeException('Failed to download release archive (' . $response->status() . ')');
        }

        if (!empty($manifest['checksum'])) {
            $actual = hash_file('sha256', $targetPath);
            if ($actual !== $manifest['checksum']) {
                throw new RuntimeException('Checksum mismatch for update archive');
            }
        }

        return $targetPath;
    }

    /**
     * Extract the release archive into a temporary directory.
     */
    protected function extractArchive(string $zipPath): string
    {
        $archive = new ZipArchive();
        if ($archive->open($zipPath) !== true) {
            throw new RuntimeException('Unable to open update archive');
        }

        $extractPath = $this->tempDir . '/' . uniqid('release_', true);
        File::ensureDirectoryExists($extractPath);

        if (!$archive->extractTo($extractPath)) {
            $archive->close();
            throw new RuntimeException('Failed to extract update archive');
        }

        $archive->close();
        return $extractPath;
    }

    /**
     * Copy files from update package into the application root.
     */
    protected function copyDirectoryContents(string $source, string $destination): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relative = ltrim(str_replace($source, '', $item->getPathname()), DIRECTORY_SEPARATOR);
            if ($relative === '' || $this->shouldSkipRelativePath($relative)) {
                continue;
            }

            $target = $destination . DIRECTORY_SEPARATOR . $relative;

            if ($item->isDir()) {
                File::ensureDirectoryExists($target);
                continue;
            }

            File::ensureDirectoryExists(dirname($target));
            File::copy($item->getPathname(), $target);
        }
    }

    /**
     * Run the list of commands defined by the manifest.
     */
    protected function runCommands(array $commands): array
    {
        $results = [];

        foreach ($commands as $command) {
            $process = Process::fromShellCommandline($command, base_path());
            $process->setTimeout(1800);
            $process->run();

            $results[] = [
                'command' => $command,
                'success' => $process->isSuccessful(),
                'output' => trim($process->getOutput()),
                'error' => trim($process->getErrorOutput()),
                'exit_code' => $process->getExitCode(),
            ];

            if (!$process->isSuccessful()) {
                throw new RuntimeException('Command failed: ' . $command . ' - ' . $process->getErrorOutput());
            }
        }

        return $results;
    }

    /**
     * Update the APP_VERSION entry inside .env and runtime config.
     */
    protected function updateAppVersion(string $version): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $contents = file_get_contents($envPath);
        if (str_contains($contents, 'APP_VERSION=')) {
            $contents = preg_replace('/APP_VERSION=.*/', 'APP_VERSION=' . $version, $contents, 1);
        } else {
            $contents .= PHP_EOL . 'APP_VERSION=' . $version;
        }

        file_put_contents($envPath, $contents);
        Config::set('app.version', $version);
    }

    /**
     * Report update status back to the mother panel.
     */
    protected function reportUpdateStatus(array $manifest, string $status, array $details = []): void
    {
        try {
            $license = License::first();
            if (!$license) {
                return;
            }

            $payload = [
                'license_key' => $license->license_key,
                'domain' => $this->resolveDomain($license),
                'version' => $manifest['version'],
                'sequence' => $manifest['sequence'] ?? null,
                'status' => $status,
                'message' => $details['message'] ?? null,
                'details' => array_filter($details),
            ];

            Http::timeout(30)->post(config('license.update.report_url'), $payload);
        } catch (Throwable $e) {
            Log::warning('Failed to report update status', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Ensure the update is applied in sequence.
     */
    protected function ensureSequenceIntegrity(array $manifest): void
    {
        $expectedPrevious = $manifest['previous_version'] ?? null;
        if ($expectedPrevious && $expectedPrevious !== $this->currentVersion()) {
            throw new RuntimeException("Update {$manifest['version']} requires previous release {$expectedPrevious}");
        }
    }

    /**
     * Skip copying sensitive/system paths.
     */
    protected function shouldSkipRelativePath(string $relative): bool
    {
        $normalized = str_replace('\\', '/', $relative);

        $blacklist = [
            '.env',
            '.env.example',
            '.git',
            'storage/',
            'vendor/',
            'node_modules/',
        ];

        foreach ($blacklist as $prefix) {
            if (str_starts_with($normalized, $prefix)) {
                return true;
            }
        }

        if (str_contains($normalized, '.git')) {
            return true;
        }

        return false;
    }

    /**
     * Resolve the current store domain for update APIs.
     */
    protected function resolveDomain(?License $license = null): string
    {
        $license ??= License::query()->first();
        $url = config('app.url', 'http://localhost');
        $host = parse_url($url, PHP_URL_HOST) ?? 'localhost';
        $port = parse_url($url, PHP_URL_PORT);

        if (!app()->runningInConsole()) {
            $request = request();
            if ($request) {
                $host = $request->getHost() ?? $host;
                $requestPort = $request->getPort();
                if ($requestPort) {
                    $port = $requestPort;
                }
            }
        }

        if ((!$port || in_array((int) $port, [80, 443], true)) && $license && $license->domain) {
            $licenseDomain = $license->domain;
            if (!preg_match('/^https?:\\/\\//i', $licenseDomain)) {
                $licenseDomain = 'http://' . $licenseDomain;
            }

            $licenseHost = parse_url($licenseDomain, PHP_URL_HOST);
            $licensePort = parse_url($licenseDomain, PHP_URL_PORT);

            if ($licenseHost && strcasecmp($licenseHost, $host) === 0 && $licensePort) {
                $port = $licensePort;
            }
        }

        if ($port && !in_array((int) $port, [80, 443], true)) {
            return "{$host}:{$port}";
        }

        return $host;
    }
}
