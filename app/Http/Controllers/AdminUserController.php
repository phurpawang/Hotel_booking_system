<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display users
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->has('role') && $request->status != '') {
            $query->where('role', $request->role);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Suspend user
     */
    public function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'suspended']);
        
        return redirect()->back()->with('success', 'User suspended successfully');
    }

    /**
     * Activate user
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        
        return redirect()->back()->with('success', 'User activated successfully');
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
