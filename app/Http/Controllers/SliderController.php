<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('position', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            // Use mimetypes without the `image` rule so AVIF passes even if GD/Imagick lacks support
            'image' => 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/avif-sequence|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'overlay_color' => 'nullable|string|max:30',
            'position' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sliders', 'public');
            $data['image'] = $imagePath;
        }

        $data['created_by'] = Auth::id();
        $data['status'] = $request->has('status') ? 1 : 0;   // Use boolean helper

        Slider::create($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            // Use mimetypes without the `image` rule so AVIF passes even if GD/Imagick lacks support
            'image' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/avif-sequence|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'overlay_color' => 'nullable|string|max:30',
            'position' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }

            $imagePath = $request->file('image')->store('sliders', 'public');
            $data['image'] = $imagePath;
        }

        $data['status'] = $request->has('status') ? 1 : 0;

        $slider->update($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete image
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider deleted successfully.');
    }

    /**
     * Update the positions of sliders.
     */
    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions', []);

        // Validate that positions are integers
        foreach ($positions as $id => $position) {
            if (!is_numeric($position)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Position values must be numeric'
                ]);
            }

            // Convert to integer to ensure proper data type
            $positions[$id] = (int) $position;
        }

        // Update each slider with its new position
        foreach ($positions as $id => $position) {
            Slider::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
