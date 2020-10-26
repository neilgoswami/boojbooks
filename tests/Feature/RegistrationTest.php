<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
	private $header = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json'
	];

	private $registerData	=	[
		'name' => 'Test User',
		'email' => 'test.user@example.com',
		'password' => 'password',
		'cpassword' => 'password'
	];

	public function testRegisterWithNoData()
	{
		$this->json('POST', 'api/register', $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'name' => [
						'The name field is required.'
					],
					'email' => [
						'The email field is required.'
					],
					'password' => [
						'The password field is required.'
					],
					'cpassword' => [
						'The cpassword field is required.'
					]
				]
			]);
	}

	public function testRegisterWithNoName()
	{
		$data = $this->registerData;
		unset($data['name']);
		$this->json('POST', 'api/register', $data, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'name' => [
						'The name field is required.'
					]
				]
			]);
	}

	public function testRegisterWithNoEmail()
	{
		$data = $this->registerData;
		unset($data['email']);
		$this->json('POST', 'api/register', $data, $this->header)
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

	public function testRegisterWithNoPassword()
	{
		$data = $this->registerData;
		unset($data['password']);
		$this->json('POST', 'api/register', $data, $this->header)
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

	public function testRegisterWithNoConfirmPassword()
	{
		$data = $this->registerData;
		unset($data['cpassword']);
		$this->json('POST', 'api/register', $data, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'cpassword' => [
						'The cpassword field is required.'
					]
				]
			]);
	}

	public function testRegisterWithMismatch()
	{
		$data = $this->registerData;
		$data['cpassword'] = 'something else';
		$this->json('POST', 'api/register', $data, $this->header)
			->assertStatus(422)
			->assertJson([
				'message' => 'The given data was invalid.',
				'errors' => [
					'cpassword' => [
						'The cpassword and password must match.'
					]
				]
			]);
	}

	public function testSuccessfullRegister()
	{
		$this->json('POST', 'api/register', $this->registerData, $this->header)
			->assertStatus(200)
			->assertJson([
				'user' => [
					'name' => $this->registerData['name'],
					'email' => $this->registerData['email'],
					'created_at' => true,
					'updated_at' => true,
					'id' => true,
				],
				'access_token' => true
			]);
	}
}
