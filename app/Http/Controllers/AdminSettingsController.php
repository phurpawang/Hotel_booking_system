<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminSettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $admin = Admin::find(Session::get('admin_id'));
        
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $admin = Admin::find(Session::get('admin_id'));
        
        $validated = $request->validate([
            'username' => 'required|string|unique:admins,username,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);
        
        // Update username
        $admin->username = $validated['username'];
        
        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            
            $admin->password = $validated['new_password']; // Will be hashed by model mutator
        }
        
        $admin->save();
        
        // Update session username
        Session::put('admin_username', $admin->username);
        
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
