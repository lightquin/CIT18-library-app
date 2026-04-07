@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div>
    <h1 class="font-display text-3xl text-stone-800 font-semibold mb-2">Create account</h1>
    <p class="text-stone-500 mb-8">Join LibraFlow to manage your library</p>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="form-input w-full px-4 py-2.5 text-stone-800 bg-white"
                   placeholder="John Doe" required autofocus>
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-input w-full px-4 py-2.5 text-stone-800 bg-white"
                   placeholder="you@example.com" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Password</label>
            <input type="password" name="password"
                   class="form-input w-full px-4 py-2.5 text-stone-800 bg-white"
                   placeholder="Min. 8 characters" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="form-input w-full px-4 py-2.5 text-stone-800 bg-white"
                   placeholder="Repeat password" required>
        </div>

        <button type="submit"
                class="btn-primary w-full py-2.5 rounded-xl text-white font-semibold text-sm">
            Create Account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Already have an account?
        <a href="{{ route('login') }}" class="text-amber-600 font-semibold hover:text-amber-700">Sign in</a>
    </p>
</div>
@endsection
