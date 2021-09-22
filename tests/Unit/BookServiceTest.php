<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use App\Repositories\Author\AuthorRepository;
use App\Repositories\Book\BookRepository;
use App\Services\Book\BookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $bookService;
    protected $bookRepository;
    protected $authorRepository;

    public function setup(): void
    {
        parent::setup();

        $this->bookRepository = new BookRepository(new Book());
        $this->authorRepository = new AuthorRepository(new Author());

        $this->bookService = new BookService($this->bookRepository, $this->authorRepository);
    }

    public function testCreateBook()
    {
        $book = [
            'name' => 'testBook',
            'isbn' => 'testisbn',
            'release_date' => 2018-1-1,
            'authors' => ['john', 'cain'],
            'country' => 'testcountry',
            'number_of_pages' => 30,
            'publisher' => 'testpublisher'
        ];

        $bookCreated = $this->bookService->createBook($book);
        $this->assertEquals($bookCreated->name, $book['name']);
        $this->assertEquals($bookCreated->isbn, $book['isbn']);
        $this->assertEquals($bookCreated->release_date, $book['release_date']);
        $this->assertEquals($bookCreated->country, $book['country']);
        $this->assertEquals($bookCreated->number_of_pages, $book['number_of_pages']);
        $this->assertEquals($bookCreated->publisher, $book['publisher']);
    }

    public function getAllBooks()
    {
        $book = [
            'name' => 'testBook',
            'isbn' => 'testisbn',
            'release_date' => 2018-1-1,
            'authors' => ['john', 'cain'],
            'country' => 'testcountry',
            'number_of_pages' => 30,
            'publisher' => 'testpublisher'
        ];

        $this->bookService->createBook($book);

        $books = $this->bookService->getAllBooks();
        $this->assertNotEmpty($books);
    }

    public function testUpdateBook()
    {
        $book = [
            'name' => 'testBook',
            'isbn' => 'testisbn',
            'release_date' => 2018-1-1,
            'authors' => ['john', 'cain'],
            'country' => 'testcountry',
            'number_of_pages' => 30,
            'publisher' => 'testpublisher'
        ];

        $bookCreated = $this->bookService->createBook($book);

        $update = ['name' => 'things fall apart'];

        $bookUpdated = $this->bookService->updateBook($update, $bookCreated->id);

        $this->assertEquals($bookUpdated->name, $update['name']);
    }

    public function testGetBookById()
    {
        $book = [
            'name' => 'testBook',
            'isbn' => 'testisbn',
            'release_date' => 2018-1-1,
            'authors' => ['john', 'cain'],
            'country' => 'testcountry',
            'number_of_pages' => 30,
            'publisher' => 'testpublisher'
        ];

        $bookCreated = $this->bookService->createBook($book);

        $bookFound = $this->bookService->getBookById($bookCreated->id);

        $this->assertNotEmpty($bookFound);
        $this->assertEquals($bookFound->name, $book['name']);
    }

    public function testDeleteBook()
    {
        $book = [
            'name' => 'testBook',
            'isbn' => 'testisbn',
            'release_date' => 2018-1-1,
            'authors' => ['john', 'cain'],
            'country' => 'testcountry',
            'number_of_pages' => 30,
            'publisher' => 'testpublisher'
        ];

        $bookCreated = $this->bookService->createBook($book);


        $this->bookService->deleteBook($bookCreated->id);
        $this->expectException(ModelNotFoundException::class);

        $bookFound = $this->bookService->getBookById($bookCreated->id);
        $this->assertEmpty($bookFound);
    }
}
