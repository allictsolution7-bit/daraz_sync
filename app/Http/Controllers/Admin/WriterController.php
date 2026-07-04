<?php

namespace App\Http\Controllers\Admin;

use App\Models\Writer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WriterController extends Controller
{
    public function index(Request $request)
    {
        $writers = Writer::orderByDesc('popularity_score')->paginate();
        return view('admin.writers.index', [
            'writers' => $writers,
        ]);
    }

    public function create()
    {
        return view('admin.writers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'email' => 'nullable|email|unique:writers,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'popularity_score' => 'nullable|integer',
        ]);

        $data = $request->except(['photo']);
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_writer_' . $photo->getClientOriginalName();
            $photo->storeAs('public/writers', $photoName);
            $data['photo'] = 'storage/writers/' . $photoName;
        }
        Writer::create($data);
        return redirect()->route('admin.writers.index')->with('success', 'Writer created successfully.');
    }

    public function edit(Writer $writer)
    {
        return view('admin.writers.edit', compact('writer'));
    }

    public function update(Request $request, Writer $writer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'email' => 'nullable|email|unique:writers,email,' . $writer->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'popularity_score' => 'nullable|integer',
        ]);
        $data = $request->except(['photo']);
        if ($request->hasFile('photo')) {
            if ($writer->photo && file_exists(public_path($writer->photo))) {
                @unlink(public_path($writer->photo));
            }
            $photo = $request->file('photo');
            $photoName = time() . '_writer_' . $photo->getClientOriginalName();
            $photo->storeAs('public/writers', $photoName);
            $data['photo'] = 'storage/writers/' . $photoName;
        }
        $writer->update($data);
        return redirect()->route('admin.writers.index')->with('success', 'Writer updated successfully.');
    }

    public function destroy(Writer $writer)
    {
        if ($writer->photo && file_exists(public_path($writer->photo))) {
            @unlink(public_path($writer->photo));
        }
        $writer->delete();
        return redirect()->route('admin.writers.index')->with('success', 'Writer deleted successfully.');
    }
}
