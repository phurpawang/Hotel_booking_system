@extends('owner.layouts.app')

@section('title', 'Edit Promotion')

@section('header')
    <div class="flex items-center mb-2">
        <a href="{{ route('owner.promotions.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Promotion</h2>
    </div>
    <p class="text-gray-600 text-sm">Update promotional offer</p>
@endsection

@section('content')
@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <p class="font-bold mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('owner.promotions.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Promotion Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $promotion->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $promotion->description) }}</textarea>
            </div>

            <div>
                <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                    Discount Percentage <span class="text-red-500">*</span>
                </label>
                <input type="number" name="discount_percentage" id="discount_percentage" min="0" max="100" value="{{ old('discount_percentage', $promotion->discount_percentage) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="applicable_room_types" class="block text-sm font-medium text-gray-700 mb-2">
                    Applicable Room Types
                </label>
                <input type="text" name="applicable_room_types" id="applicable_room_types" value="{{ old('applicable_room_types', is_array($promotion->applicable_room_types) ? implode(', ', $promotion->applicable_room_types) : '') }}" placeholder="e.g., Deluxe, Suite"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Comma-separated room types, or leave empty for all rooms</p>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Start Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    End Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('owner.promotions.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>
                Update Promotion
            </button>
        </div>
    </form>
</div>
@endsection
