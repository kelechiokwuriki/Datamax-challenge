<?php

namespace App\Repositories\Book;

use App\Book;
use App\Repositories\Base\BaseRepository;

class BookRepository extends BaseRepository
{
    public function __construct(Book $bookModel)
    {
        parent::__construct($bookModel);
    }
}
