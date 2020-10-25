<?php

namespace Database\Seeders;

use App\Models\ReadingListItem;
use Illuminate\Database\Seeder;

class ReadingListItemSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		ReadingListItem::factory()->create();
	}
}
