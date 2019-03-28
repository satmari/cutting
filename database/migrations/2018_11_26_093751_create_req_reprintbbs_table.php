<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqReprintbbsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('req_reprintbbs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('po');
			$table->string('bb');
			$table->string('module');
			$table->string('leader');
			
			$table->string('status');
			$table->string('comment')->nullable();

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
		Schema::drop('req_reprintbbs');
	}

}
