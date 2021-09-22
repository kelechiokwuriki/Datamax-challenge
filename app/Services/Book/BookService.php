<?php

namespace App\Services\Book;

use App\Book;
use App\Repositories\Author\AuthorRepository;
use App\Repositories\Book\BookRepository;
use Exception;
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

        if (empty($apiResponseResult)) {
            return [];
        }

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

    public function getAllBooks($searchTerm = null)
    {
        $books = null;

        if ($searchTerm !== null) {
            $books = $this->bookRepository->where('name', $searchTerm)
                ->orWhere('country', $searchTerm)
                ->orWhere('publisher', $searchTerm)
                ->orWhere('release_date', 'like', '%' . $searchTerm . '%')->get();

        } else {
            $books = $this->bookRepository->all();
        }

        return empty($books) ? [] : $books;
    }


    public function updateBook(array $bookData, int $bookId)
    {
        $updatedBook = $this->bookRepository->update($bookId, $bookData);

        if ($updatedBook) {
            return $this->bookRepository->find($bookId);
        }

        return [];
    }

    public function deleteBook(int $bookId)
    {
        $book = $this->bookRepository->find($bookId);

        if ($book && $this->bookRepository->delete($bookId)) {
            return $book;
        }

        return [];
    }

    private function createAuthorAndAttachToBook(Book $book, array $authors)
    {
        foreach ($authors as $author) {
            $authorCreated = $this->authorRepository->create(['name' => $author]);

            $book->authors()->sync($authorCreated->id);
        }
    }
}
