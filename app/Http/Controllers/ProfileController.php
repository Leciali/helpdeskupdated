<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->filled('display_name') ? $request->display_name : (
            $request->filled('first_name') || $request->filled('last_name') ?
            trim(($request->first_name ?? $user->first_name) . ' ' . ($request->last_name ?? $user->last_name)) :
            $user->name
        );
        $user->email = $request->email;
        $user->first_name = $request->filled('first_name') ? $request->first_name : $user->first_name;
        $user->last_name = $request->filled('last_name') ? $request->last_name : $user->last_name;
        $user->display_name = $request->filled('display_name') ? $request->display_name : $user->display_name;
        $user->about = $request->filled('about') ? $request->about : $user->about;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            $file->move(public_path('profile_photos'), $filename);
            $user->profile_photo = 'profile_photos/'.$filename;
        }
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
} 