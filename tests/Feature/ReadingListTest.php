<?php

namespace Tests\Feature;

use App\Models\ReadingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadingListTest extends TestCase
{
	private $header = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json'
	];

	public function testGetReadingListsWithoutLogin()
	{
		$user = User::factory()->create();
		$this->json('GET', 'api/lists', $this->header)
			->assertStatus(401);
	}

	public function testGetReadingLists()
	{
		$user = User::factory()->create();
		$this->actingAs($user, 'api');
		$this->json('GET', 'api/lists', $this->header)
			->assertStatus(200);
	}

	public function testAddReadingListWithNoData()
	{
		$user = User::factory()->create();

		$this->actingAs($user, 'api');
		$this->json('POST', 'api/lists', $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'name' => ['The name field is required.']
				]
			]);
	}

	public function testAddReadingWithInvalidNameFormat()
	{
		$user = User::factory()->create();
		$listName = '!@#$';
		$listData = ['name' => $listName];

		$this->actingAs($user, 'api');
		$this->json('POST', 'api/lists', $listData, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'name' => ['The name format is invalid.']
				]
			]);
	}

	public function testAddReadingListSuccessfully()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$listData = ['name' => $listName];

		$this->actingAs($user, 'api');
		$this->json('POST', 'api/lists', $listData, $this->header)
			->assertStatus(201)
			->assertJson([
				'message' => 'List added successfully.',
				'data' => [
					'user_id' => $user->id,
					'name' => $listName,
					'created_at' => true,
					'updated_at' => true,
					'id' => true
				]
			]);
	}

	public function testGetReadingListWithInvalidId()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);

		$this->actingAs($user, 'api');
		$this->json('GET', 'api/lists/123', $this->header)
			->assertStatus(404)
			->assertJson([
				'message' => 'No list found.'
			]);
	}

	public function testGetReadingList()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);

		$this->actingAs($user, 'api');
		$this->json('GET', 'api/lists/' . $readingList->id, $this->header)
			->assertStatus(200)
			->assertJson([
				'id' => $readingList->id,
				'user_id' => $user->id,
				'name' =>  $listName,
				'active' => true,
				'created_at' => true,
				'updated_at' => true,
				'reading_list_items' => []
			]);
	}

	public function testUpdateReadingListWithNoData()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$newName = 'Reading List 2';

		$this->actingAs($user, 'api');
		$this->json('PUT', 'api/lists/' . $readingList->id, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'name' => ['The name field is required.'],
				]
			]);
	}

	public function testUpdateReadingList()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$newName = 'Reading List 2';
		$newData = ['name' => $newName];

		$this->actingAs($user, 'api');
		$this->json('PUT', 'api/lists/' . $readingList->id, $newData, $this->header)
			->assertStatus(201)
			->assertJson([
				'message' => 'List updated successfully.',
				'data' => [
					'id' => true,
					'user_id' => $user->id,
					'name' => $newName,
					'active' => true,
					'created_at' => true,
					'updated_at' => true,
				]
			]);
	}

	public function testDeleteReadingListWithInvalidId()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);

		$this->actingAs($user, 'api');
		$this->json('DELETE', 'api/lists/123', $this->header)
			->assertStatus(404)
			->assertJson([
				'message' => 'No list found.'
			]);
	}

	public function testDeleteReadingListWithDifferentUserId()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);
		$otherUser = User::factory()->create();

		$this->actingAs($otherUser, 'api');
		$this->json('DELETE', 'api/lists/' . $readingList->id, $this->header)
			->assertStatus(404)
			->assertJson([
				'message' => 'No list found.'
			]);
	}

	public function testDeleteReadingList()
	{
		$user = User::factory()->create();
		$listName = 'Reading List 1';
		$readingList = ReadingList::factory()->create([
			'user_id' => $user->id,
			'name' => $listName
		]);

		$this->actingAs($user, 'api');
		$this->json('DELETE', 'api/lists/' . $readingList->id, $this->header)
			->assertStatus(200)
			->assertJson([
				'message' => 'List deleted successfully.'
			]);
	}
}
