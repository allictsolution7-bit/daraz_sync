<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Writer;
use App\Services\SettingsService;
use Illuminate\Contracts\View\View;

class WriterController extends Controller
{
    public function index(): View
    {
        $writers = Writer::where('status', 1)
            ->orderBy('name', 'asc')
            ->paginate(20);

        // Set SEO data
        $seoTitle = 'Writers - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Discover talented writers and authors. Browse our collection of books by your favorite writers.';
        $seoKeywords = 'writers, authors, books, literature, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = SettingsService::getDefaultOgImage();

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage);

        return view('frontend.writers.index', array_merge(compact('writers'), $seoData));
    }

    public function show($id, $slug): View
    {
        $writer = Writer::where('status', 1)
            ->where('id', $id)
            ->with(['books' => function($query) {
                $query->where('status', 1);
            }])
            ->firstOrFail();

        // Set SEO data
        $seoTitle = $writer->name . ' - Writer - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Explore books by ' . $writer->name . '. Discover their works and literary contributions.';
        $seoKeywords = $writer->name . ', writer, author, books, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = $writer->image ? asset('storage/' . $writer->image) : SettingsService::getDefaultOgImage();

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage);

        return view('frontend.writers.show', array_merge(compact('writer'), $seoData));
    }

    /**
     * Get SEO data for any page
     */
    private function getSeoData($title = null, $description = null, $keywords = null, $image = null, $type = 'website')
    {
        return [
            'metaTitle' => $title ?? SettingsService::getDefaultMetaTitle(),
            'metaDescription' => $description ?? SettingsService::getDefaultMetaDescription(),
            'metaKeywords' => $keywords ?? SettingsService::getDefaultMetaKeywords(),
            'ogImage' => $image ?? SettingsService::getDefaultOgImage(),
            'ogType' => $type,
            'metaRobots' => SettingsService::getRobotsMeta(),
            'canonicalUrl' => url()->current(),
        ];
    }
}
