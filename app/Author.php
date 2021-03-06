<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Book;

class Author extends Model
{
    protected $guarded = [];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
