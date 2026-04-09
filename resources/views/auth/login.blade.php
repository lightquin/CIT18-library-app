@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div class="page-content">
    <h1 class="font-display text-3xl text-ink-900 font-semibold mb-2">Welcome back</h1>
    <p class="text-ink-500 mb-8">Sign in to your LibraFlow account</p>

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
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-input w-full px-4 py-2.5 text-ink-900 bg-white"
                   placeholder="you@example.com" required autofocus>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-sm font-medium text-ink-700">Password</label>
            </div>
            <input type="password" name="password"
                   class="form-input w-full px-4 py-2.5 text-ink-900 bg-white"
                   placeholder="••••••••" required>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember"
                   class="w-4 h-4 accent-blue-600 rounded">
            <label for="remember" class="text-sm text-ink-600">Remember me</label>
        </div>

        <button type="submit"
                class="btn-primary w-full py-2.5 rounded-xl text-white font-semibold text-sm transition-all hover:shadow-lg hover:shadow-blue-500/20">
            Sign In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-ink-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">Create one</a>
    </p>

    <div class="mt-6 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
        <p class="text-xs text-blue-800 font-bold mb-1 uppercase tracking-wider">Demo credentials</p>
        <p class="text-xs text-blue-700">Email: <span class="font-medium">admin@library.com</span></p>
        <p class="text-xs text-blue-700">Password: <span class="font-medium">password</span></p>
    </div>
</div>
@endsection