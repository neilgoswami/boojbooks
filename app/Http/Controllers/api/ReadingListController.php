<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ReadingList;
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
		return response()->json(['message' => 'List added successfully.'], 201);
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
		return response()->json(['message' => 'List updated successfully.', 201]);
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
		return response()->json(['message' => 'List deleted successfully.'], 204);
	}

	public function listItems($id)
	{
		$readingList =  ReadingList::where('user_id', Auth::user()->id)
			->where('id', $id)->first();
		if (!$readingList) return response()->json(['message' => 'No list found.'], 404);

		return $readingList->readingListItems;
	}

	protected function validateList()
	{
		return request()->validate([
			'name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9 \/\-,.:]+$/']
		]);
	}
}
