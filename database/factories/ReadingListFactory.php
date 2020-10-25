<?php

namespace Database\Factories;

use App\Models\ReadingList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingListFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = ReadingList::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'user_id' => User::all()->random(),
			'name' => $this->faker->numerify('Reading List ###')
		];
	}
}
