<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = CustomerReview::latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'review_date' => 'required|date',
            'rating' => 'required|integer|min:1|max:5',
            'product_name' => 'required|string|max:255',
            'review_text' => 'required|string',
            'reviewer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ]);

        $data = $request->except(['reviewer_image', 'product_image']);
        
        // Handle reviewer image upload
        if ($request->hasFile('reviewer_image')) {
            $reviewerImage = $request->file('reviewer_image');
            $reviewerImageName = time() . '_reviewer_' . $reviewerImage->getClientOriginalName();
            $reviewerImage->storeAs('public/reviews', $reviewerImageName);
            $data['reviewer_image'] = 'storage/reviews/' . $reviewerImageName;
        }
        
        // Handle product image upload
        if ($request->hasFile('product_image')) {
            $productImage = $request->file('product_image');
            $productImageName = time() . '_product_' . $productImage->getClientOriginalName();
            $productImage->storeAs('public/reviews', $productImageName);
            $data['product_image'] = 'storage/reviews/' . $productImageName;
        }
        
        CustomerReview::create($data);
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Customer review created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerReview  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerReview $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerReview  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerReview $review)
    {
        $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'review_date' => 'required|date',
            'rating' => 'required|integer|min:1|max:5',
            'product_name' => 'required|string|max:255',
            'review_text' => 'required|string',
            'reviewer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable',
        ]);

        $data = $request->except(['reviewer_image', 'product_image']);
        $data['is_active'] = $request->has('is_active');
        
        // Handle reviewer image upload
        if ($request->hasFile('reviewer_image')) {
            // Delete old image if exists
            if ($review->reviewer_image && file_exists(public_path($review->reviewer_image))) {
                unlink(public_path($review->reviewer_image));
            }
            
            $reviewerImage = $request->file('reviewer_image');
            $reviewerImageName = time() . '_reviewer_' . $reviewerImage->getClientOriginalName();
            $reviewerImage->storeAs('public/reviews', $reviewerImageName);
            $data['reviewer_image'] = 'storage/reviews/' . $reviewerImageName;
        }
        
        // Handle product image upload
        if ($request->hasFile('product_image')) {
            // Delete old image if exists
            if ($review->product_image && file_exists(public_path($review->product_image))) {
                unlink(public_path($review->product_image));
            }
            
            $productImage = $request->file('product_image');
            $productImageName = time() . '_product_' . $productImage->getClientOriginalName();
            $productImage->storeAs('public/reviews', $productImageName);
            $data['product_image'] = 'storage/reviews/' . $productImageName;
        }
        
        $review->update($data);
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Customer review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerReview  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerReview $review)
    {
        // Delete images if they exist
        if ($review->reviewer_image && file_exists(public_path($review->reviewer_image))) {
            unlink(public_path($review->reviewer_image));
        }
        
        if ($review->product_image && file_exists(public_path($review->product_image))) {
            unlink(public_path($review->product_image));
        }
        
        $review->delete();
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Customer review deleted successfully.');
    }
    
    /**
     * Toggle the active status of the review.
     *
     * @param  \App\Models\CustomerReview  $review
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(CustomerReview $review)
    {
        $review->is_active = !$review->is_active;
        $review->save();
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review status updated successfully.');
    }
}