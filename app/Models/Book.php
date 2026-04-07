<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'description',
        'author_id',
        'category_id',
        'published_year',
        'publisher',
        'total_copies',
        'available_copies',
        'price',
        'status',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function updateStatus(): void
    {
        if ($this->available_copies <= 0) {
            $this->status = 'unavailable';
        } elseif ($this->available_copies < $this->total_copies * 0.3) {
            $this->status = 'limited';
        } else {
            $this->status = 'available';
        }
        $this->save();
    }
}
