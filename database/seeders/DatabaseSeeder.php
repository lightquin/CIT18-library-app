<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // user
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@library.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $librarian = User::create([
            'name'     => 'Jane Librarian',
            'email'    => 'librarian@library.com',
            'password' => Hash::make('password'),
            'role'     => 'librarian',
        ]);

        $member1 = User::create([
            'name'     => 'Juan dela Cruz',
            'email'    => 'juan@member.com',
            'password' => Hash::make('password'),
            'role'     => 'member',
        ]);

        $member2 = User::create([
            'name'     => 'Maria Santos',
            'email'    => 'maria@member.com',
            'password' => Hash::make('password'),
            'role'     => 'member',
        ]);

        // category
        $categories = [
            ['name' => 'Fiction',         'color' => '#6366f1', 'description' => 'Novels and short stories'],
            ['name' => 'Science',         'color' => '#0ea5e9', 'description' => 'Natural and applied sciences'],
            ['name' => 'History',         'color' => '#f97316', 'description' => 'World and local history'],
            ['name' => 'Technology',      'color' => '#10b981', 'description' => 'Computing and engineering'],
            ['name' => 'Philosophy',      'color' => '#8b5cf6', 'description' => 'Ethics, logic, metaphysics'],
            ['name' => 'Biography',       'color' => '#ec4899', 'description' => 'Life stories of notable people'],
        ];

        $cats = [];
        foreach ($categories as $cat) {
            $cats[] = Category::create(array_merge($cat, ['slug' => Str::slug($cat['name'])]));
        }

        // author
        $authors = [
            ['name' => 'Jose Rizal',          'nationality' => 'Filipino',   'birth_date' => '1861-06-19'],
            ['name' => 'Gabriel García Márquez','nationality' => 'Colombian', 'birth_date' => '1927-03-06'],
            ['name' => 'George Orwell',        'nationality' => 'British',    'birth_date' => '1903-06-25'],
            ['name' => 'Stephen Hawking',      'nationality' => 'British',    'birth_date' => '1942-01-08'],
            ['name' => 'Yuval Noah Harari',    'nationality' => 'Israeli',    'birth_date' => '1976-02-24'],
            ['name' => 'Robert C. Martin',     'nationality' => 'American',   'birth_date' => '1952-12-05'],
        ];

        $authorModels = [];
        foreach ($authors as $a) {
            $authorModels[] = Author::create($a);
        }

        // book
        $books = [
            ['title' => 'Noli Me Tangere',              'isbn' => '978-971-10-0001-1', 'author' => 0, 'category' => 0, 'year' => 1887, 'copies' => 5, 'price' => 250],
            ['title' => 'El Filibusterismo',            'isbn' => '978-971-10-0002-2', 'author' => 0, 'category' => 0, 'year' => 1891, 'copies' => 4, 'price' => 250],
            ['title' => 'One Hundred Years of Solitude','isbn' => '978-0-06-088328-7', 'author' => 1, 'category' => 0, 'year' => 1967, 'copies' => 3, 'price' => 499],
            ['title' => 'Nineteen Eighty-Four',         'isbn' => '978-0-45-228285-3', 'author' => 2, 'category' => 0, 'year' => 1949, 'copies' => 6, 'price' => 399],
            ['title' => 'Animal Farm',                  'isbn' => '978-0-45-231103-9', 'author' => 2, 'category' => 0, 'year' => 1945, 'copies' => 4, 'price' => 299],
            ['title' => 'A Brief History of Time',      'isbn' => '978-0-55-338016-3', 'author' => 3, 'category' => 1, 'year' => 1988, 'copies' => 3, 'price' => 599],
            ['title' => 'Sapiens',                      'isbn' => '978-0-06-231609-7', 'author' => 4, 'category' => 2, 'year' => 2011, 'copies' => 5, 'price' => 699],
            ['title' => 'Homo Deus',                    'isbn' => '978-0-06-246431-6', 'author' => 4, 'category' => 2, 'year' => 2015, 'copies' => 4, 'price' => 699],
            ['title' => 'Clean Code',                   'isbn' => '978-0-13-235088-4', 'author' => 5, 'category' => 3, 'year' => 2008, 'copies' => 6, 'price' => 899],
            ['title' => 'The Clean Coder',              'isbn' => '978-0-13-708107-3', 'author' => 5, 'category' => 3, 'year' => 2011, 'copies' => 4, 'price' => 799],
        ];

        $bookModels = [];
        foreach ($books as $b) {
            $bookModels[] = Book::create([
                'title'           => $b['title'],
                'isbn'            => $b['isbn'],
                'author_id'       => $authorModels[$b['author']]->id,
                'category_id'     => $cats[$b['category']]->id,
                'published_year'  => $b['year'],
                'total_copies'    => $b['copies'],
                'available_copies'=> $b['copies'],
                'price'           => $b['price'],
                'status'          => 'available',
                'created_by'      => $admin->id,
            ]);
        }

        // sample borrow
        Borrowing::create([
            'user_id'     => $member1->id,
            'book_id'     => $bookModels[0]->id,
            'borrowed_at' => now()->subDays(10),
            'due_date'    => now()->addDays(4),
            'status'      => 'borrowed',
        ]);
        $bookModels[0]->decrement('available_copies');
        $bookModels[0]->updateStatus();

        Borrowing::create([
            'user_id'     => $member2->id,
            'book_id'     => $bookModels[6]->id,
            'borrowed_at' => now()->subDays(20),
            'due_date'    => now()->subDays(6),
            'status'      => 'borrowed',
        ]);
        $bookModels[6]->decrement('available_copies');
        $bookModels[6]->updateStatus();

        Borrowing::create([
            'user_id'     => $member1->id,
            'book_id'     => $bookModels[8]->id,
            'borrowed_at' => now()->subDays(30),
            'due_date'    => now()->subDays(16),
            'returned_at' => now()->subDays(14),
            'status'      => 'returned',
        ]);
    }
}
