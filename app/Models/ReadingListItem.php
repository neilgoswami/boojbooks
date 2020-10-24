<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingListItem extends Model
{
	use HasFactory;

	public function readingList()
	{
		return $this->belongsTo(ReadingList::class);
	}
}
