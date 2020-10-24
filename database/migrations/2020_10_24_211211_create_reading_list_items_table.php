<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingListItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reading_list_items', function (Blueprint $table) {
			$table->id();
			$table->foreignId('reading_list_id')
				->references('id')
				->on('reading_lists')
				->onDelete('cascade');
			$table->foreignId('book_id')
				->references('id')
				->on('books')
				->onDelete('cascade');
			$table->integer('sort_no');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('reading_list_items');
	}
}
