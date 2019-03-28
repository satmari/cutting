<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqPadprintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('req_padprints', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('module');
			$table->integer('qty');
			$table->string('style')->nullable();
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
		Schema::drop('req_padprints');
	}

}
