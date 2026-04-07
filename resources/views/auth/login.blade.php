@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div>
    <h1 class="font-display text-3xl text-stone-800 font-semibold mb-2">Welcome back</h1>
    <p class="text-stone-500 mb-8">Sign in to your LibraFlow account</p>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-input w-full px-4 py-2.5 text-stone-800 bg-white"
                   placeholder="you@example.com" required autofocus>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-sm font-medium text-stone-700">Password</label>
            </div>
            <input type="password" name="password"
                   class="form-input w-full px-4 py-2.5 text-stone-800 bg-white"
                   placeholder="••••••••" required>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember"
                   class="w-4 h-4 accent-amber-500 rounded">
            <label for="remember" class="text-sm text-stone-600">Remember me</label>
        </div>

        <button type="submit"
                class="btn-primary w-full py-2.5 rounded-xl text-white font-semibold text-sm">
            Sign In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-amber-600 font-semibold hover:text-amber-700">Create one</a>
    </p>

    <!-- Demo credentials hint -->
    <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
        <p class="text-xs text-amber-800 font-semibold mb-1">Demo credentials</p>
        <p class="text-xs text-amber-700">Email: admin@library.com</p>
        <p class="text-xs text-amber-700">Password: password</p>
    </div>
</div>
@endsection
