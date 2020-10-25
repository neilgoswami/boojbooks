<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BookController;
use App\Http\Controllers\api\ReadingListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

// BOOK ROUTES
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);

// AUTH ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// READING LIST
Route::middleware('auth:api')->group(function () {
	Route::get('/lists', [ReadingListController::class, 'index']);
	Route::get('/lists/{readingList}', [ReadingListController::class, 'show']);
	Route::post('/lists', [ReadingListController::class, 'store']);
	Route::put('/lists/{readingList}', [ReadingListController::class, 'update']);
	Route::delete('/lists/{readingList}', [ReadingListController::class, 'destroy']);

	Route::get(
		'/lists/{readingList}/books',
		[ReadingListController::class, 'listItems']
	);
	Route::post(
		'/lists/{readingList}/books',
		[ReadingListController::class, 'addListItem']
	);
	Route::delete(
		'/lists/{readingList}/books/{book}',
		[ReadingListController::class, 'removeListItem']
	);

	Route::get(
		'/lists/{readingList}/books/{book}/up',
		[ReadingListController::class, 'moveItemUp']
	);
	Route::get(
		'/lists/{readingList}/books/{book}/down',
		[ReadingListController::class, 'moveItemDown']
	);
});
