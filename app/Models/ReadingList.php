<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingList extends Model
{
	use HasFactory;

	protected $fillable = ['name'];

	public function readingListItems()
	{
		return $this->hasMany(ReadingListItem::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function deleteList()
	{
		$this->active = false;
		return $this->save();
    }
    
    public function getAllReadingList(User $user)
    {
        return $user->readingList();
    }
}
