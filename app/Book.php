<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Author;

class Book extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at', 'updated_at',
    ];


    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}
