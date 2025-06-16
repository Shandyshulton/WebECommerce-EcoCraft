<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id_admins . ',id_admins',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email']);

        // Jika ada foto baru di-upload
        if ($request->hasFile('photo')) {
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $data['profile_image'] = $path;
        }

        DB::table('admins')->where('id_admins', $admin->id_admins)->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
