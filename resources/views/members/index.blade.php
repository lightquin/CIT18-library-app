@extends('layouts.app')
@section('title', 'Members')
@section('page-title', 'Members')

@section('content')
<div class="space-y-6">
    <div class="card p-5">
        <form method="GET" action="{{ route('members.index') }}" class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member name or email"
                       class="form-input w-full px-3 py-2.5 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary px-4 py-2.5 rounded-lg text-sm">Search</button>
                <a href="{{ route('members.index') }}" class="px-4 py-2.5 rounded-lg text-sm bg-ink-100 hover:bg-ink-200 text-ink-700">Reset</a>
                <a href="{{ route('members.create') }}" class="px-4 py-2.5 rounded-lg text-sm bg-amber-500 hover:bg-amber-600 text-white font-medium">Register Member</a>
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-3">Name</th>
                        <th class="text-left px-5 py-3">Email</th>
                        <th class="text-left px-5 py-3">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse($members as $member)
                    <tr class="hover:bg-ink-50/60">
                        <td class="px-5 py-4 font-medium text-ink-800">{{ $member->name }}</td>
                        <td class="px-5 py-4 text-ink-600">{{ $member->email }}</td>
                        <td class="px-5 py-4 text-ink-500">{{ $member->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-ink-400">No members found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-ink-100">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
