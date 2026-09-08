<?php

namespace App\Http\Controllers;

use App\Services\ImageSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageSearchController extends Controller
{
    protected ImageSearchService $imageSearchService;

    public function __construct(ImageSearchService $imageSearchService)
    {
        $this->imageSearchService = $imageSearchService;
    }

    /**
     * Search products matching an uploaded image or camera photo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $tempPath = null;

        try {
            // Check if uploaded as file
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,webp,avif,gif|max:12288', // up to 12MB from phone camera
                ]);

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension() ?: 'jpg';
                $tempFileName = 'img_search_' . Str::random(16) . '.' . $extension;
                $tempPath = storage_path('app/temp/' . $tempFileName);

                if (!file_exists(storage_path('app/temp'))) {
                    @mkdir(storage_path('app/temp'), 0755, true);
                }

                $file->move(storage_path('app/temp'), $tempFileName);

            } elseif ($request->filled('image_base64')) {
                // Base64 payload from camera capture / canvas
                $base64Data = $request->input('image_base64');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $type = strtolower($type[1]);
                } else {
                    $type = 'jpg';
                }

                $binaryData = base64_decode($base64Data);
                if (!$binaryData) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid image data provided.',
                        'products' => [],
                    ], 422);
                }

                if (!file_exists(storage_path('app/temp'))) {
                    @mkdir(storage_path('app/temp'), 0755, true);
                }

                $tempFileName = 'img_search_' . Str::random(16) . '.' . $type;
                $tempPath = storage_path('app/temp/' . $tempFileName);
                file_put_contents($tempPath, $binaryData);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file or photo provided.',
                    'products' => [],
                ], 400);
            }

            // Downscale image if it exceeds 1200px to maintain peak speed and save memory
            $this->optimizeTempImage($tempPath);

            // Execute visual search
            $results = $this->imageSearchService->searchByImage($tempPath, 12);

            return response()->json([
                'success' => true,
                'count' => count($results),
                'products' => $results,
            ]);

        } catch (\Throwable $e) {
            Log::error('Visual product search failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Visual search could not process the photo. ' . $e->getMessage(),
                'products' => [],
            ], 500);

        } finally {
            // Ensure temporary image is removed
            if ($tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    /**
     * Rebuild precomputed catalog hashes cache.
     *
     * @return JsonResponse
     */
    public function rebuildHashes(): JsonResponse
    {
        $count = $this->imageSearchService->rebuildCatalogHashes();

        return response()->json([
            'success' => true,
            'message' => "Successfully indexed visual features for {$count} products.",
            'count' => $count,
        ]);
    }

    /**
     * Optimize and scale down large uploaded camera photos for faster hashing.
     */
    protected function optimizeTempImage(string $filePath): void
    {
        if (!file_exists($filePath) || !extension_loaded('gd')) {
            return;
        }

        $info = @getimagesize($filePath);
        if (!$info) {
            return;
        }

        [$width, $height, $type] = $info;

        // If dimensions are within 600px, no resize needed
        if ($width <= 600 && $height <= 600) {
            return;
        }

        $maxDim = 600;
        $ratio = min($maxDim / $width, $maxDim / $height);
        $newWidth = (int) ($width * $ratio);
        $newHeight = (int) ($height * $ratio);

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($filePath),
            IMAGETYPE_PNG => @imagecreatefrompng($filePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : null,
            default => null,
        };

        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        // Overwrite temp file with optimized version
        imagejpeg($dst, $filePath, 85);
        imagedestroy($dst);
    }
}
