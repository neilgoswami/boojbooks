<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ReadingList;
use App\Models\ReadingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadingListItemTest extends TestCase
{
	private $header = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json'
	];

	public function testGetReadingListItemsWithoutLogin()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$this->json('GET', 'api/lists/' . $readingList->id . '/books', $this->header)
			->assertStatus(401);
	}

	public function testGetReadingListItemsWithInvalidListId()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$this->actingAs($user, 'api');
		$this->json('GET', 'api/lists/123/books', $this->header)
			->assertStatus(404)
			->assertJson([
				'message' => 'No books found in the list.'
			]);
	}

	public function testGetReadingListItems()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$this->actingAs($user, 'api');
		$this->json('GET', 'api/lists/' . $readingList->id . '/books', $this->header)
			->assertStatus(200);
	}

	public function testAddReadingListItemSuccessfully()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$book = Book::factory()->create();
		$listItemData = ['book_id' => $book->id];

		$this->actingAs($user, 'api');
		$this->json('POST', 'api/lists/' . $readingList->id . '/books', $listItemData, $this->header)
			->assertStatus(201)
			->assertJson([
				'message' => 'Book added to list successfully.',
				'data' => [
					'reading_list_id' => $readingList->id,
					'book_id' => $book->id,
					'sort_no' => true,
					'created_at' => true,
					'updated_at' => true,
					'id' => true
				]
			]);
	}

	public function testDeleteReadingListItemSuccessfully()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$book = Book::factory()->create();
		$item = ReadingListItem::factory()->create(['book_id' => $book->id]);

		$this->actingAs($user, 'api');
		$this->json('DELETE', 'api/lists/' . $readingList->id . '/books/' . $item->id, $this->header)
			->assertStatus(200)
			->assertJson([
				'message' => 'Book deleted from list successfully.'
			]);
	}
}
