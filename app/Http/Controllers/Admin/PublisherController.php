<?php

namespace App\Http\Controllers\Admin;

use App\Models\publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $publishers = Publisher::paginate(10);
        return view('admin.publishers.index', [
            'publishers' => $publishers,
        ]);
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'email' => 'nullable|email|unique:publishers,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'detail' => 'nullable|string',
        ]);

        $data = $request->except(['logo']);
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_publisher_' . $logo->getClientOriginalName();
            $logo->storeAs('public/publishers', $logoName);
            $data['logo'] = 'storage/publishers/' . $logoName;
        }
        Publisher::create($data);
        return redirect()->route('admin.publishers.index')->with('success', 'publisher created successfully.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'email' => 'nullable|email|unique:publishers,email,' . $publisher->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'detail' => 'nullable|string',
            'popularity_score' => 'nullable|integer',
        ]);
        $data = $request->except(['logo']);
        if ($request->hasFile('logo')) {
            if ($publisher->logo && file_exists(public_path($publisher->logo))) {
                @unlink(public_path($publisher->logo));
            }
            $logo = $request->file('logo');
            $logoName = time() . '_publisher_' . $logo->getClientOriginalName();
            $logo->storeAs('public/publishers', $logoName);
            $data['logo'] = 'storage/publishers/' . $logoName;
        }
        $publisher->update($data);
        return redirect()->route('admin.publishers.index')->with('success', 'publisher updated successfully.');
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->logo && file_exists(public_path($publisher->logo))) {
            @unlink(public_path($publisher->logo));
        }
        $publisher->delete();
        return redirect()->route('admin.publishers.index')->with('success', 'publisher deleted successfully.');
    }
}
