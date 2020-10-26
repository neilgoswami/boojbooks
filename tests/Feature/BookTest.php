<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
	private $header = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json'
	];

	public function testGetBooks()
	{
		$this->json('GET', 'api/books', $this->header)
			->assertStatus(200);
	}

	public function testGetBookWithInvalidId()
	{
		$book = Book::factory()->create();
		$this->json('GET', 'api/books/123' . $book->id, $this->header)
			->assertStatus(404);
	}

	public function testGetBook()
	{
		$book = Book::factory()->create();
		$this->json('GET', 'api/books/' . $book->id, $this->header)
			->assertStatus(200);
	}
}
