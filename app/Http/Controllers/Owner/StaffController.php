<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\BhutanPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        $staff = User::where('hotel_id', $hotel->id)
            ->whereIn('role', ['MANAGER', 'RECEPTION'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('owner.staff.index', compact('hotel', 'staff'));
    }

    public function create()
    {
        $hotel = $this->getOwnerHotel();
        return view('owner.staff.create', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel = $this->getOwnerHotel();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => ['required', new BhutanPhoneNumber()],
            'role' => 'required|in:MANAGER,RECEPTION',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'hotel_id' => $hotel->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'status' => 'ACTIVE',
        ]);

        return redirect()->route('owner.staff.index')
            ->with('success', 'Staff member added successfully!');
    }

    public function edit($id)
    {
        $hotel = $this->getOwnerHotel();
        $staff = User::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        return view('owner.staff.edit', compact('hotel', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $hotel = $this->getOwnerHotel();
        $staff = User::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => ['required', new BhutanPhoneNumber()],
            'role' => 'required|in:MANAGER,RECEPTION',
        ]);

        $staff->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('owner.staff.index')
            ->with('success', 'Staff member updated successfully!');
    }

    public function destroy($id)
    {
        $hotel = $this->getOwnerHotel();
        $staff = User::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $staff->delete();

        return redirect()->route('owner.staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }
}
