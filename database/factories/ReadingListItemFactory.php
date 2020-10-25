<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\ReadingList;
use App\Models\ReadingListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingListItemFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = ReadingListItem::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$readingList = ReadingList::all()->random();
		$sortNumber = ReadingListItem::getNextSortNo($readingList);
		return [
			'reading_list_id' => $readingList,
			'book_id' => Book::all()->random(),
			'sort_no' => $sortNumber
		];
	}
}
