<?php

namespace App\Services\Book;

use App\Book;
use App\Repositories\Author\AuthorRepository;
use App\Repositories\Book\BookRepository;
use Illuminate\Support\Facades\Log;

class BookService
{
    protected $bookRepository;
    protected $authorRepository;

    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    public function createBook(array $book)
    {
        $authors = $book['authors'];
        unset($book['authors']);

        $bookCreated = $this->bookRepository->create($book);

        $this->createAuthorAndAttachToBook($bookCreated, $authors);

        return $bookCreated;
    }

    private function createAuthorAndAttachToBook(Book $book, array $authors)
    {
        foreach ($authors as $author) {
            $authorCreated = $this->authorRepository->create(['name' => $author]);

            $book->authors()->sync($authorCreated->id);
        }
    }
}
