<?php

namespace App\Services\Book;

use App\Book;
use App\Repositories\Author\AuthorRepository;
use App\Repositories\Book\BookRepository;
use Illuminate\Support\Facades\Http;
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

    public function queryExternalBook(string $nameQuery)
    {
        $baseApi = config('app.external_books_api');
        $url = $baseApi.'/api/books/?name='.$nameQuery;

        $response = Http::get($url);
        $apiResponseResult = $response->json();

        $result = array_map(function ($res) {
            unset($res['url']);
            $res['number_of_pages'] = $res['numberOfPages'];

            unset($res['numberOfPages']);
            unset($res['mediaType']);

            $res['release_date'] = $res['released'];
            unset($res['released']);

            unset($res['characters']);
            unset($res['povCharacters']);

            return $res;

        }, $apiResponseResult);

        return $result;
    }

    private function createAuthorAndAttachToBook(Book $book, array $authors)
    {
        foreach ($authors as $author) {
            $authorCreated = $this->authorRepository->create(['name' => $author]);

            $book->authors()->sync($authorCreated->id);
        }
    }
}