@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome -->
    <div class="rounded-2xl p-6 text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, #1a1712 0%, #4a4236 100%);">
        <div class="relative z-10">
            <p class="text-amber-300 text-sm font-medium mb-1">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},</p>
            <h2 class="font-display text-2xl font-semibold">{{ auth()->user()->name }}</h2>
            <p class="text-ink-300 text-sm mt-1">Here's what's happening in your library today.</p>
        </div>
        <!-- Decorative circle -->
        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-amber-500/10"></div>
        <div class="absolute -right-4 -bottom-12 w-56 h-56 rounded-full bg-amber-500/5"></div>
    </div>

    <!-- Stats grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
        $statCards = [
            ['label' => 'Total Books',      'value' => $stats['total_books'],       'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'text-blue-600 bg-blue-50'],
            ['label' => 'Available',         'value' => $stats['available_books'],   'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-green-600 bg-green-50'],
            ['label' => 'Members',           'value' => $stats['total_members'],     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-purple-600 bg-purple-50'],
            ['label' => 'Borrowed',          'value' => $stats['active_borrowings'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'text-amber-600 bg-amber-50'],
            ['label' => 'Overdue',           'value' => $stats['overdue_books'],     'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-red-600 bg-red-50'],
            ['label' => 'Categories',        'value' => $stats['total_categories'],  'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'color' => 'text-indigo-600 bg-indigo-50'],
        ];
        @endphp

        @foreach($statCards as $card)
        <div class="card p-4 flex flex-col gap-3">
            <div class="w-9 h-9 rounded-lg {{ explode(' ', $card['color'])[1] }} flex items-center justify-center">
                <svg class="w-5 h-5 {{ explode(' ', $card['color'])[0] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-ink-800">{{ $card['value'] }}</p>
                <p class="text-xs text-ink-400 mt-0.5">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Two column layout -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- Recent Books -->
        <div class="card">
            <div class="flex items-center justify-between p-5 border-b border-ink-100">
                <h3 class="font-display text-base font-semibold text-ink-800">Recently Added Books</h3>
                <a href="{{ route('books.index') }}" class="text-amber-600 text-sm font-medium hover:text-amber-700">View all →</a>
            </div>
            <div class="divide-y divide-ink-50">
                @forelse($recentBooks as $book)
                <div class="p-4 flex items-center gap-4 hover:bg-ink-50/50 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 text-amber-700 font-display font-bold text-sm">
                        {{ strtoupper(substr($book->title, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-ink-800 truncate">{{ $book->title }}</p>
                        <p class="text-xs text-ink-400">{{ $book->author->name }} · {{ $book->category->name }}</p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium badge-{{ $book->status }}">
                        {{ ucfirst($book->status) }}
                    </span>
                </div>
                @empty
                <div class="p-8 text-center text-ink-400 text-sm">No books added yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Borrowings -->
        <div class="card">
            <div class="flex items-center justify-between p-5 border-b border-ink-100">
                <h3 class="font-display text-base font-semibold text-ink-800">Recent Borrowings</h3>
                <a href="#" class="text-amber-600 text-sm font-medium hover:text-amber-700">View all →</a>
            </div>
            <div class="divide-y divide-ink-50">
                @forelse($recentBorrowings as $borrowing)
                <div class="p-4 flex items-center gap-4 hover:bg-ink-50/50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 text-purple-700 font-semibold text-sm">
                        {{ strtoupper(substr($borrowing->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-ink-800 truncate">{{ $borrowing->book->title }}</p>
                        <p class="text-xs text-ink-400">{{ $borrowing->user->name }} · Due {{ $borrowing->due_date->format('M d') }}</p>
                    </div>
                    @if($borrowing->isOverdue())
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-red-100 text-red-700">Overdue</span>
                    @else
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-blue-100 text-blue-700">Active</span>
                    @endif
                </div>
                @empty
                <div class="p-8 text-center text-ink-400 text-sm">No borrowings yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="card p-5">
        <h3 class="font-display text-base font-semibold text-ink-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('books.create') }}"
               class="btn-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Book
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-2.5 bg-ink-100 hover:bg-ink-200 text-ink-700 rounded-xl text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Register Member
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-2.5 bg-ink-100 hover:bg-ink-200 text-ink-700 rounded-xl text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Record Borrowing
            </a>
        </div>
    </div>

</div>
@endsection
