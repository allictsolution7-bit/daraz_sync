<?php

namespace App\View\Components;

use App\Services\LicenseService;
use Illuminate\View\Component;

class LicenseFeature extends Component
{
    public string $module;
    public bool $enabled = false;
    public ?string $message = null;

    protected $licenseService;

    public function __construct(string $module, LicenseService $licenseService)
    {
        $this->module = $module;
        $this->licenseService = $licenseService;
        
        // Initialize properties with safe defaults
        $this->enabled = false;
        $this->message = null;
        
        try {
            $this->enabled = $licenseService->isModuleAllowed($module);
            
            if (!$this->enabled) {
                $this->message = "The '{$module}' module is not available in your current license.";
            }
        } catch (\Exception $e) {
            // Fallback to false if there's an error
            $this->enabled = false;
            $this->message = "Error checking license status: " . $e->getMessage();
        }
    }

    public function render()
    {
        // Ensure all properties are properly initialized
        if (!isset($this->enabled)) {
            $this->enabled = false;
        }
        if (!isset($this->message)) {
            $this->message = null;
        }
        
        return view('components.license-feature');
    }
}
