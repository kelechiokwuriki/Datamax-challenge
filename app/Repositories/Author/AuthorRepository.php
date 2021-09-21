<?php

namespace App\Repositories\Author;

use App\Author;
use App\Repositories\Base\BaseRepository;

class AuthorRepository extends BaseRepository
{
    public function __construct(Author $authorModel)
    {
        parent::__construct($authorModel);
    }
}
