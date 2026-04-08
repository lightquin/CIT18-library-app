@extends('layouts.app')
@section('title', 'Record Borrowing')
@section('page-title', 'Record Borrowing')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <h2 class="font-display text-xl text-ink-800 mb-1">New Borrowing</h2>
        <p class="text-sm text-ink-500 mb-6">Assign an available book to a registered member.</p>

        <form method="POST" action="{{ route('borrowings.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Member</label>
                    <select name="user_id" class="form-input w-full px-3 py-2.5" required>
                        <option value="">Select member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>{{ $member->name }} ({{ $member->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Book</label>
                    <select name="book_id" class="form-input w-full px-3 py-2.5" required>
                        <option value="">Select available book</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>{{ $book->title }} ({{ $book->available_copies }} left)</option>
                        @endforeach
                    </select>
                    @error('book_id')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Borrowed Date</label>
                    <input type="date" name="borrowed_at" value="{{ old('borrowed_at', now()->toDateString()) }}" class="form-input w-full px-3 py-2.5" required>
                    @error('borrowed_at')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(14)->toDateString()) }}" class="form-input w-full px-3 py-2.5" required>
                    @error('due_date')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="form-input w-full px-3 py-2.5">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg text-sm">Save Borrowing</button>
                <a href="{{ route('borrowings.index') }}" class="px-5 py-2.5 rounded-lg text-sm bg-ink-100 hover:bg-ink-200 text-ink-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
