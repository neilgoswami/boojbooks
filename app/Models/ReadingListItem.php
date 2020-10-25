<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingListItem extends Model
{
	use HasFactory;

	protected $fillable = ['reading_list_id', 'book_id', 'sort_no'];

	public function readingList()
	{
		return $this->belongsTo(ReadingList::class);
	}

	public static function getNextSortNo(ReadingList $readingList)
	{
		$maxSort = ReadingListItem::where('reading_list_id', $readingList->id)
			->orderBy('sort_no', 'desc')->pluck('sort_no')->first();
		return $maxSort ? $maxSort + 1 : 1;
	}
}
