@extends('layouts.app')
@section('title', 'Register Member')
@section('page-title', 'Register Member')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <h2 class="font-display text-xl text-ink-800 mb-1">New Member</h2>
        <p class="text-sm text-ink-500 mb-6">Create a member account that can borrow books.</p>

        <form method="POST" action="{{ route('members.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full px-3 py-2.5" required>
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-input w-full px-3 py-2.5" required>
                @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Password</label>
                    <input type="password" name="password" class="form-input w-full px-3 py-2.5" required>
                    @error('password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input w-full px-3 py-2.5" required>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg text-sm">Create Member</button>
                <a href="{{ route('members.index') }}" class="px-5 py-2.5 rounded-lg text-sm bg-ink-100 hover:bg-ink-200 text-ink-700">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
