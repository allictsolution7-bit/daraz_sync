<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website' => 'nullable|url',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $brandData = [
            'name' => $request->input('name'),
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'website' => $request->input('website'),
            'status' => $request->has('status'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = 'brands/' . time() . '-' . $logo->getClientOriginalName();
            $logo->storeAs('public', $logoPath);
            $brandData['logo'] = $logoPath;
        }

        $brand = Brand::create($brandData);

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully.',
                'brand' => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $brand->slug
                ]
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website' => 'nullable|url',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $brandData = [
            'name' => $request->input('name'),
            'slug' => $request->input('slug') ?: Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'website' => $request->input('website'),
            'status' => $request->has('status'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            
            $logo = $request->file('logo');
            $logoPath = 'brands/' . time() . '-' . $logo->getClientOriginalName();
            $logo->storeAs('public', $logoPath);
            $brandData['logo'] = $logoPath;
        }

        $brand->update($brandData);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        // Delete logo if exists
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:brands,id'],
        ]);

        $brands = Brand::whereIn('id', $validated['ids'])->get();

        if ($brands->isEmpty()) {
            return redirect()->back()->with('error', 'No brands found for deletion.');
        }

        $deletedCount = 0;

        foreach ($brands as $brand) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $brand->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            return redirect()->back()->with('success', "{$deletedCount} brand(s) deleted successfully.");
        }

        return redirect()->back()->with('warning', 'No brands were deleted.');
    }

    public function checkSlugAvailability(Request $request)
    {
        $slug = $request->input('slug');
        
        if (empty($slug)) {
            return response()->json([
                'available' => false,
                'message' => 'Slug cannot be empty'
            ]);
        }
        
        // Check if slug exists in brands table
        $exists = Brand::where('slug', $slug)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug already exists' : 'Slug is available'
        ]);
    }
}
