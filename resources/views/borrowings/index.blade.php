@extends('layouts.app')
@section('title', 'Borrowings')
@section('page-title', 'Borrowings')

@section('content')
<div class="space-y-6">
    <div class="card p-5">
        <form method="GET" action="{{ route('borrowings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search member or book"
                   class="form-input md:col-span-2 px-3 py-2.5 text-sm">

            <select name="status" class="form-input px-3 py-2.5 text-sm">
                <option value="">All statuses</option>
                <option value="borrowed" @selected(request('status') === 'borrowed')>Borrowed</option>
                <option value="returned" @selected(request('status') === 'returned')>Returned</option>
                <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary px-4 py-2.5 rounded-lg text-sm">Filter</button>
                <a href="{{ route('borrowings.index') }}" class="px-4 py-2.5 rounded-lg text-sm bg-ink-100 hover:bg-ink-200 text-ink-700">Reset</a>
                <a href="{{ route('borrowings.create') }}" class="px-4 py-2.5 rounded-lg text-sm bg-amber-500 hover:bg-amber-600 text-white font-medium">Record</a>
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-3">Book</th>
                        <th class="text-left px-5 py-3">Member</th>
                        <th class="text-left px-5 py-3">Due Date</th>
                        <th class="text-left px-5 py-3">Status</th>
                        <th class="text-right px-5 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse($borrowings as $borrowing)
                    @php
                        $isOverdue = $borrowing->status === 'borrowed' && $borrowing->due_date->isPast();
                    @endphp
                    <tr class="hover:bg-ink-50/60">
                        <td class="px-5 py-4 font-medium text-ink-800">{{ $borrowing->book->title }}</td>
                        <td class="px-5 py-4 text-ink-600">{{ $borrowing->user->name }}</td>
                        <td class="px-5 py-4 text-ink-600">{{ $borrowing->due_date->format('M d, Y') }}</td>
                        <td class="px-5 py-4">
                            @if($borrowing->status === 'returned')
                                <span class="text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-medium">Returned</span>
                            @elseif($isOverdue)
                                <span class="text-xs px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-medium">Overdue</span>
                            @else
                                <span class="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">Borrowed</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            @if($borrowing->status !== 'returned')
                                <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs bg-ink-100 hover:bg-ink-200 text-ink-700 font-medium">
                                        Mark Returned
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-ink-400">Completed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-ink-400">No borrowings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-ink-100">
            {{ $borrowings->links() }}
        </div>
    </div>
</div>
@endsection
