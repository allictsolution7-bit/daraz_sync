<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function create(Menu $menu)
    {
        $menuItems = $menu->allMenuItems;
        return view('admin.menu-items.create', compact('menu', 'menuItems'));
    }

    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'icon_class' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'has_mega_menu' => 'nullable',
            'mega_menu_config' => 'nullable|array',
            'mega_menu_config.column_1' => 'nullable|integer|exists:menus,id',
            'mega_menu_config.column_2' => 'nullable|integer|exists:menus,id',
            'mega_menu_config.column_3' => 'nullable|integer|exists:menus,id',
            'mega_menu_config.column_4' => 'nullable|integer|exists:menus,id',
            'mega_menu_headers' => 'nullable|array',
        ]);

        // Only allow mega menu for top-level items (check for truthy value)
        $hasMegaMenu = $request->filled('has_mega_menu') && empty($request->parent_id);

        $menuItem = $menu->allMenuItems()->create([
            'parent_id' => $request->parent_id,
            'title' => $request->title,
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'icon_class' => $request->icon_class,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
            'has_mega_menu' => $hasMegaMenu,
            'mega_menu_config' => $hasMegaMenu ? $request->mega_menu_config : null,
            'mega_menu_headers' => $hasMegaMenu ? $request->mega_menu_headers : null,
        ]);

        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Menu item created successfully!',
                'data' => $menuItem
            ]);
        }

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(Menu $menu, MenuItem $menuItem)
    {
        $menuItems = $menu->allMenuItems()->where('id', '!=', $menuItem->id)->get();
        return view('admin.menu-items.edit', compact('menu', 'menuItem', 'menuItems'));
    }

    public function update(Request $request, Menu $menu, MenuItem $menuItem)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'icon_class' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'has_mega_menu' => 'nullable',
            'mega_menu_config' => 'nullable|array',
            'mega_menu_config.column_1' => 'nullable|integer|exists:menus,id',
            'mega_menu_config.column_2' => 'nullable|integer|exists:menus,id',
            'mega_menu_config.column_3' => 'nullable|integer|exists:menus,id',
            'mega_menu_config.column_4' => 'nullable|integer|exists:menus,id',
            'mega_menu_headers' => 'nullable|array',
        ]);

        // Prevent circular reference
        if ($request->parent_id && $menuItem->id == $request->parent_id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A menu item cannot be its own parent.'
                ], 422);
            }
            return back()->withErrors(['parent_id' => 'A menu item cannot be its own parent.']);
        }

        // Only allow mega menu for top-level items (check for truthy value)
        $hasMegaMenu = $request->filled('has_mega_menu') && empty($request->parent_id);

        $menuItem->update([
            'parent_id' => $request->parent_id,
            'title' => $request->title,
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'icon_class' => $request->icon_class,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
            'has_mega_menu' => $hasMegaMenu,
            'mega_menu_config' => $hasMegaMenu ? $request->mega_menu_config : null,
            'mega_menu_headers' => $hasMegaMenu ? $request->mega_menu_headers : null,
        ]);

        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Menu item updated successfully!',
                'data' => $menuItem
            ]);
        }

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $menu, MenuItem $menuItem)
    {
        $menuItem->delete();

        // Check if request is AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully!'
            ]);
        }

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        try {
            $items = json_decode($request->input('items'), true);

            if (!$items) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data provided'
                ]);
            }

            $this->updateMenuItemOrder($items);

            return response()->json([
                'success' => true,
                'message' => 'Menu order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function updateMenuItemOrder($items, $parentId = null, $order = 0)
    {
        foreach ($items as $item) {
            $order++;
            $menuItem = MenuItem::findOrFail($item['id']);
            $menuItem->parent_id = $parentId;
            $menuItem->order = $order;
            $menuItem->save();

            if (isset($item['children']) && count($item['children']) > 0) {
                $this->updateMenuItemOrder($item['children'], $menuItem->id, 0);
            }
        }
    }
}
