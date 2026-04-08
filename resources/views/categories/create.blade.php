@extends('layouts.app')
@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <h2 class="font-display text-xl text-ink-800 mb-1">New Category</h2>
        <p class="text-sm text-ink-500 mb-6">Create a category for organizing books.</p>

        <form method="POST" action="{{ route('categories.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Category Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full px-3 py-2.5" required>
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Description</label>
                <textarea name="description" rows="4" class="form-input w-full px-3 py-2.5">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Color</label>
                <input type="color" name="color" value="{{ old('color', '#d97706') }}" class="h-11 w-20 border border-ink-200 rounded-lg">
                @error('color')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg text-sm">Save Category</button>
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 rounded-lg text-sm bg-ink-100 hover:bg-ink-200 text-ink-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
