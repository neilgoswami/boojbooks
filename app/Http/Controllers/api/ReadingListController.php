<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingList;
use App\Models\ReadingListItem;
use Illuminate\Support\Facades\Auth;

class ReadingListController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return ReadingList::where('user_id', Auth::user()->id)
			->where('active', true)
			->get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		$validatedData = $this->validateList();

		$readingList = new ReadingList;
		$readingList->user_id = Auth::user()->id;
		$readingList->name = $validatedData['name'];
		$readingList->save();
		return response()->json([
			'message' => 'List added successfully.',
			'data' => $readingList
		], 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\ReadingList  $readingList
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return response()->json(['message' => 'No list found.'], 404);

		$readingList->reading_list_items = $readingList->readingListItems;
		return $readingList;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\ReadingList  $readingList
	 * @return \Illuminate\Http\Response
	 */
	public function update(ReadingList $readingList)
	{
		$validatedData = $this->validateList();
		$readingList->name = $validatedData['name'];
		$readingList->update();
		return response()->json([
			'message' => 'List updated successfully.',
			'data' => $readingList
		], 201);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\ReadingList  $readingList
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return response()->json(['message' => 'No list found.'], 404);

		$readingList->deleteList();
		return response()->json(['message' => 'List deleted successfully.'], 200);
	}

	public function listItems($id)
	{
		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return response()->json(['message' => 'No books found in the list.'], 404);

		return $readingList->readingListItems;
	}

	public function addListItem($id)
	{
		// CHECK IF BOOK EXISTS
		$book = Book::find(request('book_id'));
		if (!$book) return response()->json(['message' => 'No book found.'], 404);

		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return response()->json(['message' => 'No list found.'], 404);

		// GET LAST SORT NUMBER
		$sortNumber = ReadingListItem::getNextSortNo($readingList);

		$readingListItem = ReadingListItem::create([
			'reading_list_id' => $id,
			'book_id' => request('book_id'),
			'sort_no' => $sortNumber
		]);

		return response()->json([
			'message' => 'Book added to list successfully.',
			'data' => $readingListItem
		], 201);
	}

	public function removeListItem($id, $bookId)
	{
		// CHECK IF BOOK EXISTS
		$book = Book::find($bookId);
		if (!$book) return response()->json(['message' => 'No book found.'], 404);

		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return response()->json(['message' => 'No list found.'], 404);

		ReadingListItem::where('reading_list_id', $id)
			->where('book_id', $bookId)
			->delete();
		return response()->json(['message' => 'Book deleted from list successfully.'], 200);
	}

	public function moveItemUp($id, $bookId)
	{
		$readingListItem = $this->checkAndGetItem($id, $bookId);
		if (!$readingListItem)
			return response()->json(['message' => 'Invalid request'], 404);
		if ($readingListItem->sort_no === 1)
			return response()->json(['message' => 'Item is first.'], 200);

		$readingListItem->sort_no -= 1;
		$readingListItem->save();

		$swapReadingListItem = ReadingListItem::where('reading_list_id', $id)
			->where('sort_no', $readingListItem->sort_no)->first();
		$swapReadingListItem->sort_no += 1;
		$swapReadingListItem->save();

		return response()->json(['message' => 'Item moved up.']);
	}

	public function moveItemDown($id, $bookId)
	{
		$readingListItem = $this->checkAndGetItem($id, $bookId);
		if (!$readingListItem)
			return response()->json(['message' => 'Invalid request'], 404);

		$maxSort = ReadingListItem::where('reading_list_id', $id)
			->orderBy('sort_no', 'desc')->pluck('sort_no')->first();
		if ($readingListItem->sort_no === $maxSort)
			return response()->json(['message' => 'Item is last.'], 200);

		$readingListItem->sort_no += 1;
		$readingListItem->save();

		$swapReadingListItem = ReadingListItem::where('reading_list_id', $id)
			->where('sort_no', $readingListItem->sort_no)->first();
		$swapReadingListItem->sort_no -= 1;
		$swapReadingListItem->save();

		return response()->json(['message' => 'Item moved down.']);
	}

	protected function validateList()
	{
		return request()->validate([
			'name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9 \/\-,.:]+$/']
		]);
	}

	protected function checkAndGetItem($id, $bookId)
	{
		// CHECK READING LIST EXISTS FOR USER
		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return false;

		// CHECK READING LIST ITEM EXISTS FOR THE LIST
		$readingListItem = ReadingListItem::where('reading_list_id', $readingList->id)
			->where('book_id', $bookId)->first();
		if (!$readingListItem) return false;

		return $readingListItem;
	}
}
