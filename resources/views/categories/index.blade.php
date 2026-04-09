@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="space-y-6">
    <div class="card p-5">
        <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by category name"
                       class="form-input w-full px-3 py-2.5 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary px-4 py-2.5 rounded-lg text-sm">Search</button>
                <a href="{{ route('categories.index') }}" class="px-4 py-2.5 rounded-lg text-sm bg-ink-100 hover:bg-ink-200 text-ink-700">Reset</a>
                <a href="{{ route('categories.create') }}" class="px-4 py-2.5 rounded-lg text-sm bg-blue-500 hover:bg-blue-600 text-white font-medium">Add Category</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($categories as $category)
        <div class="card p-5 card-hover">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-display text-lg text-ink-800">{{ $category->name }}</h3>
                <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
            </div>
            <p class="text-sm text-ink-500 min-h-11">{{ $category->description ?: 'No description provided.' }}</p>
            <div class="mt-4 text-xs text-ink-400">{{ $category->books_count }} books</div>
        </div>
        @empty
        <div class="card p-8 text-center text-ink-400 md:col-span-2 xl:col-span-3">No categories found.</div>
        @endforelse
    </div>

    <div class="card p-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
