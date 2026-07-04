<?php

    namespace App\Http\Controllers;

    use App\Models\Social;
    use Illuminate\Http\Request;

    class SocialController extends Controller {
        /**
         * Display a listing of the resource.
         */
        public function index () {
            $socials = Social::all ();
            return view ("admin.socials.index", compact ('socials'));
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store (Request $request) {
            $request->validate ([
                "name"  => "required",
                "url"   => "required",
                "class" => "required",
            ]);

            $social = Social::create ([
                "name"   => $request->name,
                "url"    => $request->input ('url'),
                "class"  => $request->input ('class'),
                "status" => $request->input ("status"),
            ]);
            if ($social) {
                flash ("Social added.");
            } else {
                flash ("social not added", "error");
            }
            return redirect ()->route ("admin.socials.index");
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create () {
            return view ("admin.socials.create");
        }

        /**
         * Display the specified resource.
         */
        public function show (Social $social) {
            //
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit (Social $social) {
            return view ("admin.socials.edit", compact ("social"));
        }

        /**
         * Update the specified resource in storage.
         */
        public function update (Request $request, Social $social) {
            $request->validate ([
                "name"  => "required",
                "url"   => "required",
                "class" => "required",
            ]);
            $social->name = $request->name;
            $social->url = $request->url;
            $social->class = $request->class;
            $social->status = $request->status;
            $social->save ();
            flash ("social updated.");
            return redirect ()->back ();
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy (Social $social) {
            $social->delete ();
            flash ("social deleted");
            return redirect ()->back ();
        }
    }
