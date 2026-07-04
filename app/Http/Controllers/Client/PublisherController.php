<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Services\SettingsService;
use Illuminate\Contracts\View\View;

class PublisherController extends Controller
{
    public function index(): View
    {
        $publishers = Publisher::where('status', 1)
            ->orderBy('name', 'asc')
            ->paginate(20);

        // Set SEO data
        $seoTitle = 'Publishers - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Discover renowned publishers and their book collections. Browse books from top publishing houses.';
        $seoKeywords = 'publishers, publishing houses, books, literature, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = SettingsService::getDefaultOgImage();

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage);

        return view('frontend.publishers.index', array_merge(compact('publishers'), $seoData));
    }

    public function show($id, $slug): View
    {
        $publisher = Publisher::where('status', 1)
            ->where('id', $id)
            ->with(['books' => function($query) {
                $query->where('status', 1);
            }])
            ->firstOrFail();

        // Set SEO data
        $seoTitle = $publisher->name . ' - Publisher - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Explore books published by ' . $publisher->name . '. Discover quality publications from this renowned publisher.';
        $seoKeywords = $publisher->name . ', publisher, books, publishing house, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = $publisher->logo ? asset('storage/' . $publisher->logo) : SettingsService::getDefaultOgImage();

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage);

        return view('frontend.publishers.show', array_merge(compact('publisher'), $seoData));
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
