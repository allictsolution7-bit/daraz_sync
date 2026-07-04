<?php

namespace App\Helpers;

use App\Models\Menu;

class MenuHelper
{
    public static function renderMenu($location)
    {
        $menu = Menu::where('location', $location)->where('status', true)->first();
        
        if (!$menu) {
            return '';
        }
        
        $menuItems = $menu->menuItems()->where('status', true)->with('children')->get();
        
        return view('partials.menu', compact('menu', 'menuItems'))->render();
    }
}