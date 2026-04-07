@extends('layouts.app')
@section('title', 'Books')
@section('page-title', 'Books')

@section('content')
<div class="space-y-5">

    <!-- Header actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-ink-500 text-sm">{{ $books->total() }} books found</p>
        <a href="{{ route('books.create') }}"
           class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Book
        </a>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form method="GET" action="{{ route('books.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-input w-full px-4 py-2.5 text-ink-800 text-sm bg-white"
                       placeholder="Search by title, ISBN, or author...">
            </div>
            <select name="category" class="form-input px-4 py-2.5 text-ink-700 text-sm bg-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="form-input px-4 py-2.5 text-ink-700 text-sm bg-white">
                <option value="">All Status</option>
                <option value="available"   {{ request('status') === 'available'   ? 'selected' : '' }}>Available</option>
                <option value="limited"     {{ request('status') === 'limited'     ? 'selected' : '' }}>Limited</option>
                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">Filter</button>
            @if(request()->hasAny(['search','category','status']))
                <a href="{{ route('books.index') }}" class="px-4 py-2.5 bg-ink-100 hover:bg-ink-200 text-ink-700 rounded-xl text-sm font-medium transition-colors text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Books grid -->
    @if($books->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($books as $book)
        <div class="card card-hover flex flex-col">
            <!-- Book color header -->
            <div class="h-2 rounded-t-xl" style="background-color: {{ $book->category->color ?? '#6366f1' }}"></div>
            <div class="p-5 flex flex-col flex-1">
                <!-- Category + Status -->
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-ink-400 font-medium">{{ $book->category->name }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium badge-{{ $book->status }}">
                        {{ ucfirst($book->status) }}
                    </span>
                </div>

                <!-- Title -->
                <h3 class="font-display text-base font-semibold text-ink-800 mb-1 line-clamp-2 leading-snug">
                    {{ $book->title }}
                </h3>
                <p class="text-sm text-ink-500 mb-1">{{ $book->author->name }}</p>
                <p class="text-xs text-ink-400 mb-4">ISBN: {{ $book->isbn }}</p>

                <!-- Copies indicator -->
                <div class="mt-auto">
                    <div class="flex items-center justify-between text-xs text-ink-500 mb-1.5">
                        <span>Copies available</span>
                        <span class="font-semibold">{{ $book->available_copies }}/{{ $book->total_copies }}</span>
                    </div>
                    <div class="w-full bg-ink-100 rounded-full h-1.5">
                        @php
                            $pct = $book->total_copies > 0 ? ($book->available_copies / $book->total_copies) * 100 : 0;
                            $barColor = $pct > 50 ? '#22c55e' : ($pct > 20 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div class="h-1.5 rounded-full transition-all"
                             style="width: {{ $pct }}%; background-color: {{ $barColor }}"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-ink-100">
                    <a href="{{ route('books.show', $book) }}"
                       class="flex-1 text-center text-xs font-medium text-ink-600 hover:text-amber-600 py-1.5 rounded-lg hover:bg-amber-50 transition-colors">
                        View
                    </a>
                    <a href="{{ route('books.edit', $book) }}"
                       class="flex-1 text-center text-xs font-medium text-ink-600 hover:text-blue-600 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('books.destroy', $book) }}"
                          onsubmit="return confirm('Delete \'{{ addslashes($book->title) }}\'? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs font-medium text-ink-400 hover:text-red-600 py-1.5 px-2 rounded-lg hover:bg-red-50 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $books->links() }}
    </div>

    @else
    <!-- Empty state -->
    <div class="card p-16 text-center">
        <div class="w-16 h-16 bg-ink-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h3 class="font-display text-lg font-semibold text-ink-700 mb-2">No books found</h3>
        <p class="text-ink-400 text-sm mb-6">
            {{ request()->hasAny(['search','category','status']) ? 'Try adjusting your filters.' : 'Start by adding your first book to the library.' }}
        </p>
        <a href="{{ route('books.create') }}"
           class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add First Book
        </a>
    </div>
    @endif

</div>
@endsection
