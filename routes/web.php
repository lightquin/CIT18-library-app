<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books CRUD
    Route::resource('books', BookController::class);

    // Members
    Route::resource('members', MemberController::class)->only(['index', 'create', 'store']);

    // Borrowings
    Route::resource('borrowings', BorrowingController::class)->only(['index', 'create', 'store']);
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'markReturned'])
        ->name('borrowings.return');

    // Categories
    Route::resource('categories', CategoryController::class)->only(['index', 'create', 'store']);
});
