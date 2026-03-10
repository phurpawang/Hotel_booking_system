@extends('owner.layouts.app')

@section('title', 'Amenities')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Amenities & Facilities</h2>
        <p class="text-gray-600 text-sm">Manage your hotel amenities</p>
    </div>
    <a href="{{ route('owner.amenities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Add New Amenity
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($amenities as $amenity)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                    <i class="{{ $amenity->icon }}"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $amenity->name }}</h3>
                    @if($amenity->is_active)
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
        <p class="text-gray-600 text-sm mb-4">{{ $amenity->description }}</p>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('owner.amenities.edit', $amenity->id) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('owner.amenities.destroy', $amenity->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this amenity?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <i class="fas fa-concierge-bell text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Amenities Added</h3>
        <p class="text-gray-500 mb-4">Add amenities to showcase your hotel facilities.</p>
        <a href="{{ route('owner.amenities.create') }}" class="text-blue-600 hover:text-blue-800">
            Add your first amenity
        </a>
    </div>
    @endforelse
</div>

@if($amenities->hasPages())
<div class="mt-8">
    {{ $amenities->links() }}
</div>
@endif
@endsection
