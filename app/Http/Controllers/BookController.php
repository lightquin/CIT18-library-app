<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhereHas('author', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books      = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'isbn'           => 'required|string|max:20|unique:books,isbn',
            'description'    => 'nullable|string',
            'author_name'    => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'publisher'      => 'nullable|string|max:255',
            'total_copies'   => 'required|integer|min:1',
            'price'          => 'nullable|numeric|min:0',
        ]);

        $author = Author::firstOrCreate([
            'name' => trim($validated['author_name']),
        ]);

        $validated['author_id'] = $author->id;
        unset($validated['author_name']);

        $validated['available_copies'] = $validated['total_copies'];
        $validated['created_by']       = Auth::id();
        $validated['status']           = 'available';

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book "' . $validated['title'] . '" has been added successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['author', 'category', 'creator', 'borrowings.user']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'isbn'           => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'description'    => 'nullable|string',
            'author_name'    => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'publisher'      => 'nullable|string|max:255',
            'total_copies'   => 'required|integer|min:1',
            'price'          => 'nullable|numeric|min:0',
        ]);

        $author = Author::firstOrCreate([
            'name' => trim($validated['author_name']),
        ]);

        $validated['author_id'] = $author->id;
        unset($validated['author_name']);

        // Adjust available copies proportionally
        $diff = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $diff);

        $book->update($validated);
        $book->updateStatus();

        return redirect()->route('books.show', $book)
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $title = $book->title;

        // Check if book has active borrowings
        if ($book->borrowings()->where('status', 'borrowed')->exists()) {
            return back()->with('error', 'Cannot delete book with active borrowings.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book "' . $title . '" has been deleted.');
    }
}
