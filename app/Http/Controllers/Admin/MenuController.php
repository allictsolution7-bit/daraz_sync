<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Menu::create([
            'name' => $request->name,
            'location' => $request->location,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $menuItems = $menu->menuItems()->with('allChildren')->get();
        $allMenus = Menu::where('status', true)->withCount('menuItems')->get();
        return view('admin.menus.edit', compact('menu', 'menuItems', 'allMenus'));
    }

    /**
     * Get available menus for mega menu configuration (AJAX)
     */
    public function getAvailableMenus(Request $request)
    {
        $menus = Menu::where('status', true)
            ->select('id', 'name', 'location')
            ->withCount('menuItems')
            ->get();

        return response()->json([
            'success' => true,
            'menus' => $menus
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $menu->update([
            'name' => $request->name,
            'location' => $request->location,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }
}