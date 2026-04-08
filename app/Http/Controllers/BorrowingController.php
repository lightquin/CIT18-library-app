<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
            });
        }

        $borrowings = $query->latest()->paginate(12)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->orderBy('name')->get();
        $books = Book::where('available_copies', '>', 0)->orderBy('title')->get();

        return view('borrowings.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrowed_at',
            'notes' => 'nullable|string',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->available_copies < 1) {
            return back()->withInput()->with('error', 'Selected book has no available copies.');
        }

        Borrowing::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrowed_at' => $validated['borrowed_at'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'borrowed',
            'fine_amount' => 0,
        ]);

        $book->decrement('available_copies');
        $book->refresh();
        $book->updateStatus();

        return redirect()->route('borrowings.index')
            ->with('success', 'Borrowing recorded successfully.');
    }

    public function markReturned(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return back()->with('error', 'This borrowing is already marked as returned.');
        }

        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now()->toDateString(),
        ]);

        $book = $borrowing->book;
        $book->increment('available_copies');
        $book->refresh();
        $book->updateStatus();

        return back()->with('success', 'Borrowing marked as returned.');
    }
}
