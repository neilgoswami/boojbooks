<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
	private $header = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json'
	];

	private $loginData	=	[
		'email' => 'test.user@example.com',
		'password' => 'password'
	];

	public function testLoginWithNoData()
	{
		$this->json('POST', 'api/login', $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'email' => [
						'The email field is required.'
					],
					'password' => [
						'The password field is required.'
					]
				]
			]);
	}

	public function testLoginWithNoEmail()
	{
		$data = $this->loginData;
		unset($data['email']);
		$this->json('POST', 'api/login', $data, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'email' => [
						'The email field is required.'
					]
				]
			]);
	}

	public function testLoginWithNoPassword()
	{
		$data = $this->loginData;
		unset($data['password']);
		$this->json('POST', 'api/login', $data, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'password' => [
						'The password field is required.'
					]
				]
			]);
	}

	public function testLoginWithInvalidEmail()
	{
		$data = $this->loginData;
		$data['email'] = 'wrong@email.com';
		$this->json('POST', 'api/login', $data, $this->header)
			->assertStatus(401)
			->assertJson([
				'message' => 'Invalid credentials.'
			]);
	}

	public function testLoginWithInvalidPassword()
	{
		$data = $this->loginData;
		$data['password'] = 'something else';
		$this->json('POST', 'api/login', $data, $this->header)
			->assertStatus(401)
			->assertJson([
				'message' => 'Invalid credentials.'
			]);
	}

	public function testSuccessfullLogin()
	{
		$user = User::factory()->create([
			'email' => $this->loginData['email'],
			'password' => bcrypt($this->loginData['password'])
		]);

		$data = $this->loginData;
		$this->json('POST', 'api/login', $data, $this->header)
			->assertStatus(200)
			->assertJson([
				'user' => [
					'id' => true,
					'name' => true,
					'email' => $user['email'],
					'created_at' => true,
					'updated_at' => true
				],
				'access_token' => true
			]);
	}
}
