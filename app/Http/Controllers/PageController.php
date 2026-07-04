<?php

    namespace App\Http\Controllers;

    use App\Models\Page;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Validation\Rule;

    class PageController extends Controller {
        /**
         * Display a listing of the resource.
         */
        public function index () {
            $pages = Page::all ();
            return view ("admin.pages.index", compact ("pages"));
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store (Request $request) {
            $rules = [
                "title"   => "required|string",
                "content" => "required",
                "seo.meta_title" => "nullable|string|max:60",
                "seo.meta_description" => "nullable|string|max:160",
                "seo.meta_keywords" => "nullable|string",
                "seo.canonical_url" => "nullable|url",
                "seo.meta_robots" => ["nullable", "string", Rule::in(['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow'])],
                "seo.og_image_alt" => "nullable|string|max:255",
                "seo.schema_markup" => "nullable|string",
            ];

            // Only validate og_image if a file is actually uploaded
            if ($request->hasFile('seo.og_image') && $request->file('seo.og_image')->isValid()) {
                $rules["seo.og_image"] = "file|mimes:jpeg,png,jpg,gif,webp|max:2048";
            }

            $request->validate ($rules, [
                'seo.meta_robots.in' => 'The selected meta robots value is invalid. Please select a valid option.',
                'seo.og_image.mimes' => 'The OG image must be a file of type: jpeg, png, jpg, gif, webp.',
                'seo.og_image.max' => 'The OG image may not be greater than 2048 kilobytes.',
            ]);

            // Generate unique slug
            $baseSlug = $request->input('slug') ?: Str::slug($request->title);
            $slug = Page::generateSlug($baseSlug);

            $data = [
                "title"   => $request->title,
                "slug"    => $slug,
                "content" => $request->input ('content'),
                "status"  => $request->input ("status"),
            ];

            // Handle SEO data
            $seoData = [];
            if ($request->has('seo')) {
                $seoData = $request->input('seo');
            }

            // Handle OG image upload
            if ($request->hasFile('seo.og_image')) {
                $ogImage = $request->file('seo.og_image');
                $ogImagePath = $ogImage->store('pages/seo', 'public');
                $seoData['og_image'] = $ogImagePath;
                $data['og_image'] = $ogImagePath; // Store in individual field too
            }

            // Store individual SEO fields as well
            if ($request->has('seo.meta_title')) {
                $data['meta_title'] = $request->input('seo.meta_title');
            }
            if ($request->has('seo.meta_description')) {
                $data['meta_description'] = $request->input('seo.meta_description');
            }
            if ($request->has('seo.meta_keywords')) {
                $data['meta_keywords'] = $request->input('seo.meta_keywords');
            }
            if ($request->has('seo.canonical_url')) {
                $data['canonical_url'] = $request->input('seo.canonical_url');
            }
            if ($request->has('seo.meta_robots')) {
                $data['meta_robots'] = $request->input('seo.meta_robots');
            }
            if ($request->has('seo.og_image_alt')) {
                $data['og_image_alt'] = $request->input('seo.og_image_alt');
            }
            if ($request->has('seo.schema_markup')) {
                $data['schema_markup'] = $request->input('seo.schema_markup');
            }

            // Store SEO data as JSON
            if (!empty($seoData)) {
                $data['seo'] = $seoData;
            }

            Page::create ($data);
            flash ("Page Create successfully.");
            return redirect ()->route ("admin.pages.index");
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create () {
            return view ("admin.pages.create");
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit (Page $page) {
            return view ("admin.pages.edit", compact ("page"));
        }

        /**
         * Update the specified resource in storage.
         */
        public function update (Request $request, Page $page) {
            $rules = [
                "title"   => "required|string",
                "content" => "required",
                "seo.meta_title" => "nullable|string|max:60",
                "seo.meta_description" => "nullable|string|max:160",
                "seo.meta_keywords" => "nullable|string",
                "seo.canonical_url" => "nullable|url",
                "seo.meta_robots" => ["nullable", "string", Rule::in(['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow'])],
                "seo.og_image_alt" => "nullable|string|max:255",
                "seo.schema_markup" => "nullable|string",
            ];

            // Only validate og_image if a file is actually uploaded
            if ($request->hasFile('seo.og_image') && $request->file('seo.og_image')->isValid()) {
                $rules["seo.og_image"] = "file|mimes:jpeg,png,jpg,gif,webp|max:2048";
            }

            $request->validate ($rules, [
                'seo.meta_robots.in' => 'The selected meta robots value is invalid. Please select a valid option.',
                'seo.og_image.mimes' => 'The OG image must be a file of type: jpeg, png, jpg, gif, webp.',
                'seo.og_image.max' => 'The OG image may not be greater than 2048 kilobytes.',
            ]);

            $page->title = $request->title;
            $page->status = $request->status;
            $page->content = $request->input ("content");
            
            // Update slug if provided or if title changed
            if ($request->has('slug') && !empty($request->slug)) {
                $page->slug = Page::generateSlug($request->slug);
            } elseif ($page->isDirty('title')) {
                $page->slug = Page::generateSlug($page->title);
            }

            // Handle SEO data
            $seoData = $page->seo ?? [];
            if ($request->has('seo')) {
                $seoData = array_merge($seoData, $request->input('seo'));
                
                // Handle OG image upload
                if ($request->hasFile('seo.og_image')) {
                    // Delete old image if exists
                    if (isset($seoData['og_image']) && Storage::disk('public')->exists($seoData['og_image'])) {
                        Storage::disk('public')->delete($seoData['og_image']);
                    }
                    
                    $ogImage = $request->file('seo.og_image');
                    $ogImagePath = $ogImage->store('pages/seo', 'public');
                    $seoData['og_image'] = $ogImagePath;
                    $page->og_image = $ogImagePath; // Store in individual field too
                } elseif ($request->has('seo.existing_og_image')) {
                    // Keep existing image if no new one uploaded
                    $seoData['og_image'] = $request->input('seo.existing_og_image');
                    $page->og_image = $request->input('seo.existing_og_image');
                }
            }

            // Update individual SEO fields as well
            if ($request->has('seo.meta_title')) {
                $page->meta_title = $request->input('seo.meta_title');
            }
            if ($request->has('seo.meta_description')) {
                $page->meta_description = $request->input('seo.meta_description');
            }
            if ($request->has('seo.meta_keywords')) {
                $page->meta_keywords = $request->input('seo.meta_keywords');
            }
            if ($request->has('seo.canonical_url')) {
                $page->canonical_url = $request->input('seo.canonical_url');
            }
            if ($request->has('seo.meta_robots')) {
                $page->meta_robots = $request->input('seo.meta_robots');
            }
            if ($request->has('seo.og_image_alt')) {
                $page->og_image_alt = $request->input('seo.og_image_alt');
            }
            if ($request->has('seo.schema_markup')) {
                $page->schema_markup = $request->input('seo.schema_markup');
            }

            // Store SEO data as JSON
            if (!empty($seoData)) {
                $page->seo = $seoData;
            }

            $page->save ();
            flash ('Page Update successfully.');
            return redirect ()->back ();
        }

        /**
         * Check if a slug is available for pages
         */
        public function checkSlugAvailability(Request $request)
        {
            $slug = $request->input('slug');
            $excludeId = $request->input('exclude_id');
            
            if (empty($slug)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Slug cannot be empty'
                ]);
            }
            
            // Check if slug exists in pages table
            $query = Page::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $exists = $query->exists();
            
            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'Slug already exists' : 'Slug is available'
            ]);
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy (Page $page) {
            $page->delete ();
            flash ("Page deleted.");
            return redirect ()->back ();
        }
    }
