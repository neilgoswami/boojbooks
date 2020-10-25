<?php

namespace Database\Seeders;

use App\Models\ReadingList;
use Illuminate\Database\Seeder;

class ReadingListSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		ReadingList::factory()->count(3)->create();
	}
}
