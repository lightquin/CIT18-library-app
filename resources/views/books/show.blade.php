@extends('layouts.app')
@section('title', $book->title)
@section('page-title', 'Book Details')

@section('content')
<div class="max-w-4xl space-y-5">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-ink-400">
        <a href="{{ route('books.index') }}" class="hover:text-amber-600 transition-colors">Books</a>
        <span>/</span>
        <span class="text-ink-600 truncate">{{ $book->title }}</span>
    </nav>

    <!-- Book header card -->
    <div class="card overflow-hidden">
        <!-- Color band -->
        <div class="h-2" style="background-color: {{ $book->category->color ?? '#6366f1' }}"></div>

        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs px-3 py-1 rounded-full font-medium"
                              style="background-color: {{ $book->category->color ?? '#6366f1' }}20; color: {{ $book->category->color ?? '#6366f1' }}">
                            {{ $book->category->name }}
                        </span>
                        <span class="text-xs px-3 py-1 rounded-full font-medium badge-{{ $book->status }}">
                            {{ ucfirst($book->status) }}
                        </span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-semibold text-ink-800 mb-2">{{ $book->title }}</h1>
                    <p class="text-ink-500 text-lg mb-1">by {{ $book->author->name }}</p>
                    @if($book->author->nationality)
                        <p class="text-ink-400 text-sm">{{ $book->author->nationality }}</p>
                    @endif
                </div>

                <!-- Action buttons -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('books.edit', $book) }}"
                       class="flex items-center gap-2 px-4 py-2 bg-ink-100 hover:bg-ink-200 text-ink-700 rounded-xl text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('books.destroy', $book) }}"
                          onsubmit="return confirm('Delete this book permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            @if($book->description)
            <p class="text-ink-600 text-sm leading-relaxed mt-4 pt-4 border-t border-ink-100">
                {{ $book->description }}
            </p>
            @endif
        </div>
    </div>

    <!-- Details grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
        $details = [
            ['label' => 'ISBN',           'value' => $book->isbn],
            ['label' => 'Publisher',      'value' => $book->publisher ?? '—'],
            ['label' => 'Published Year', 'value' => $book->published_year],
            ['label' => 'Total Copies',   'value' => $book->total_copies],
            ['label' => 'Available',      'value' => $book->available_copies],
            ['label' => 'Price',          'value' => $book->price ? '₱' . number_format($book->price, 2) : '—'],
        ];
        @endphp

        @foreach($details as $detail)
        <div class="card p-4">
            <p class="text-xs text-ink-400 uppercase tracking-wider mb-1">{{ $detail['label'] }}</p>
            <p class="text-ink-800 font-semibold">{{ $detail['value'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Copy availability bar -->
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-ink-700 text-sm">Copy Availability</h3>
            <span class="text-sm text-ink-500">{{ $book->available_copies }} of {{ $book->total_copies }} available</span>
        </div>
        @php
            $pct = $book->total_copies > 0 ? ($book->available_copies / $book->total_copies) * 100 : 0;
            $barColor = $pct > 50 ? '#22c55e' : ($pct > 20 ? '#f59e0b' : '#ef4444');
        @endphp
        <div class="w-full bg-ink-100 rounded-full h-3">
            <div class="h-3 rounded-full transition-all"
                 style="width: {{ $pct }}%; background-color: {{ $barColor }}"></div>
        </div>
    </div>

    <!-- Borrowing history -->
    @if($book->borrowings->count())
    <div class="card">
        <div class="p-5 border-b border-ink-100">
            <h3 class="font-display text-base font-semibold text-ink-800">Borrowing History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-ink-100">
                        <th class="text-left px-5 py-3 text-xs text-ink-400 uppercase tracking-wider font-medium">Member</th>
                        <th class="text-left px-5 py-3 text-xs text-ink-400 uppercase tracking-wider font-medium">Borrowed</th>
                        <th class="text-left px-5 py-3 text-xs text-ink-400 uppercase tracking-wider font-medium">Due Date</th>
                        <th class="text-left px-5 py-3 text-xs text-ink-400 uppercase tracking-wider font-medium">Returned</th>
                        <th class="text-left px-5 py-3 text-xs text-ink-400 uppercase tracking-wider font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-50">
                    @foreach($book->borrowings as $b)
                    <tr class="hover:bg-ink-50/50">
                        <td class="px-5 py-3 text-ink-700 font-medium">{{ $b->user->name }}</td>
                        <td class="px-5 py-3 text-ink-500">{{ $b->borrowed_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-ink-500">{{ $b->due_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-ink-500">{{ $b->returned_at ? $b->returned_at->format('M d, Y') : '—' }}</td>
                        <td class="px-5 py-3">
                            @if($b->status === 'returned')
                                <span class="text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-medium">Returned</span>
                            @elseif($b->isOverdue())
                                <span class="text-xs px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-medium">Overdue</span>
                            @else
                                <span class="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">Borrowed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Meta info -->
    <div class="text-xs text-ink-400 flex items-center gap-4">
        <span>Added by {{ $book->creator->name }} on {{ $book->created_at->format('F d, Y') }}</span>
        @if($book->updated_at != $book->created_at)
            <span>· Updated {{ $book->updated_at->diffForHumans() }}</span>
        @endif
    </div>

</div>
@endsection
