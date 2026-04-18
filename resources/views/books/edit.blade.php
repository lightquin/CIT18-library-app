@extends('layouts.app')
@section('title', 'Edit Book')
@section('page-title', 'Edit Book')

@section('content')
<div class="max-w-3xl">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-ink-400 mb-6">
        <a href="{{ route('books.index') }}" class="hover:text-amber-600 transition-colors">Books</a>
        <span>/</span>
        <a href="{{ route('books.show', $book) }}" class="hover:text-amber-600 transition-colors truncate max-w-xs">{{ $book->title }}</a>
        <span>/</span>
        <span class="text-ink-600">Edit</span>
    </nav>

    <div class="card p-8">
        <h2 class="font-display text-xl font-semibold text-ink-800 mb-6">Edit Book Details</h2>

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
            <p class="font-semibold text-sm mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('books.update', $book) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title & ISBN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           placeholder="Book title" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">
                        ISBN <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           placeholder="e.g. 978-3-16-148410-0" required>
                </div>
            </div>

            <!-- Author & Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">
                        Author <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="author_name" value="{{ old('author_name', $book->author->name) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           placeholder="e.g. George Orwell" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" class="form-input w-full px-4 py-2.5 text-ink-700 bg-white text-sm" required>
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Publisher & Year -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Publisher</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           placeholder="Publisher name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">
                        Published Year <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="published_year"
                           value="{{ old('published_year', $book->published_year) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           min="1000" max="{{ date('Y') }}" required>
                </div>
            </div>

            <!-- Copies & Price -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">
                        Total Copies <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_copies"
                           value="{{ old('total_copies', $book->total_copies) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           min="1" required>
                    <p class="text-xs text-ink-400 mt-1">Currently {{ $book->available_copies }} available out of {{ $book->total_copies }} total.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Price (₱)</label>
                    <input type="number" name="price"
                           value="{{ old('price', $book->price) }}"
                           class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm"
                           placeholder="0.00" min="0" step="0.01">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Description</label>
                <textarea name="description" rows="4"
                          class="form-input w-full px-4 py-2.5 text-ink-800 bg-white text-sm resize-none"
                          placeholder="Brief summary of the book...">{{ old('description', $book->description) }}</textarea>
            </div>

            <!-- Form actions -->
            <div class="flex items-center justify-between pt-2 border-t border-ink-100">
                <a href="{{ route('books.show', $book) }}"
                   class="px-5 py-2.5 text-sm font-medium text-ink-600 hover:text-ink-800 bg-ink-100 hover:bg-ink-200 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold">
                    Update Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
