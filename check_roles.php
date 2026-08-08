<?php
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Check roles table
$roles = DB::table('roles')->get();
echo "=== Roles in DB ===\n";
foreach($roles as $r) { echo "ID: {$r->id}, Name: {$r->name}\n"; }

// Check wholeseller users
$wholesellers = User::whereHas('roles', function($q){ $q->where('name','wholeseller'); })->get(['id','name']);
echo "\n=== Wholeseller Users ===\n";
foreach($wholesellers as $u) { echo "ID: {$u->id}, Name: {$u->name}\n"; }

// Also check if any user has a type/user_type column
$firstUser = DB::table('users')->first();
echo "\n=== Users table columns ===\n";
echo implode(', ', array_keys((array)$firstUser)) . "\n";
