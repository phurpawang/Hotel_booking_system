<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        $user = Auth::user();
        return view('reception.profile.index', compact('user'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->back()
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Upload profile picture
     */
    public function uploadPhoto(Request $request)
    {
        $validated = $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo && Storage::exists($user->profile_photo)) {
            Storage::delete($user->profile_photo);
        }

        // Upload new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $user->update(['profile_photo' => $path]);

        return redirect()->back()
            ->with('success', 'Profile photo updated successfully.');
    }
}
