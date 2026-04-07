<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books'      => Book::count(),
            'available_books'  => Book::where('status', 'available')->count(),
            'total_members'    => User::where('role', 'member')->count(),
            'active_borrowings'=> Borrowing::where('status', 'borrowed')->count(),
            'overdue_books'    => Borrowing::where('status', 'borrowed')
                                    ->where('due_date', '<', now())
                                    ->count(),
            'total_categories' => Category::count(),
        ];

        $recentBooks      = Book::with(['author', 'category'])->latest()->take(5)->get();
        $recentBorrowings = Borrowing::with(['user', 'book'])->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'recentBooks', 'recentBorrowings'));
    }
}
