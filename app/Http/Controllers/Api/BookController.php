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

    public function queryExternalBook(Request $request)
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
}
