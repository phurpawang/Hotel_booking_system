@extends('owner.layouts.app')

@section('title', 'Promotions')

@section('header')
    <div class="flex items-center justify-between w-full">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Promotions & Discounts</h2>
            <p class="text-gray-600 text-sm">Manage your promotional offers</p>
        </div>
        <a href="{{ route('owner.promotions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Add New Promotion
        </a>
    </div>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

<div class="space-y-6">
    @forelse($promotions as $promotion)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $promotion->title }}</h3>
                    @if($promotion->isActive())
                        <span class="ml-3 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                    @else
                        <span class="ml-3 px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                            Inactive
                        </span>
                    @endif
                </div>
                <p class="text-gray-600 mb-4">{{ $promotion->description }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Discount</p>
                        <p class="text-lg font-semibold text-blue-600">{{ $promotion->discount_percentage }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Start Date</p>
                        <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($promotion->start_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">End Date</p>
                        <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($promotion->end_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Room Types</p>
                        <p class="text-sm font-medium">{{ is_array($promotion->applicable_room_types) ? implode(', ', $promotion->applicable_room_types) : 'All' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2 ml-4">
                <a href="{{ route('owner.promotions.edit', $promotion->id) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit text-xl"></i>
                </a>
                <form action="{{ route('owner.promotions.destroy', $promotion->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this promotion?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Promotions Created</h3>
        <p class="text-gray-500 mb-4">Create promotional offers to attract more guests.</p>
        <a href="{{ route('owner.promotions.create') }}" class="text-blue-600 hover:text-blue-800">
            Create your first promotion
        </a>
    </div>
    @endforelse
</div>

@if($promotions->hasPages())
<div class="mt-8">
    {{ $promotions->links() }}
</div>
@endif
@endsection
