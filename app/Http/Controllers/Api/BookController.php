<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Book\BookService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function queryExternalBooks(Request $request)
    {
        $nameQuery = $request->query('name');

        if (!$nameQuery) {
            return $this->sendError('Name query missing', [], 400);
        }

        try {
            $booksQueried = $this->bookService->queryExternalBook($nameQuery);

            return $this->sendResponse('success', $booksQueried, 200);

        } catch (Exception $e) {
            Log::error('Failed to query book. Error: '. $e->getMessage());
            return $this->sendError('Failed to query book', [], 400);
        }
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        try {
            $books = $this->bookService->getAllBooks($search);

            return $this->sendResponse('success', $books, 200);

        } catch (Exception $e) {
            Log::error('Failed to get books. Error: '. $e->getMessage());
            return $this->sendError('Failed to get books', [], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $book = $this->bookService->createBook($request->all());

            return $this->sendResponse('success', ['book' => $book], 201);

        } catch (Exception $e) {
            Log::error('Failed to create book. Error: '. $e->getMessage());
            return $this->sendError('unable to create book', [], 400);
        }
    }

    public function update(Request $request, int $bookId)
    {
        if (!$bookId) {
            return $this->sendError('Book ID missing', [], 400);
        }

        try {
            $book = $this->bookService->updateBook($request->all(), $bookId);

            $message = 'The book '. $book->name. ' was updated successfully';

            return $this->sendResponse($message, $book, 200);

        } catch (Exception $e) {
            Log::error('Failed to update book. Error: '. $e->getMessage());
            return $this->sendError('unable to update book', [], 400);
        }
    }

}
