<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Contracts\View\View;

class ClientController extends Controller
{
    public function contact(): View
    {
        // Set SEO data
        $seoTitle = 'Contact Us - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Get in touch with us. We\'re here to help with any questions or concerns you may have.';
        $seoKeywords = 'contact, support, help, customer service';
        $seoImage = SettingsService::getDefaultOgImage();

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage);

        return view('frontend.contact', $seoData);
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
