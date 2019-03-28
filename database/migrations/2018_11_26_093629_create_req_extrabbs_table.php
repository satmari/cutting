<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqExtrabbsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('req_extrabbs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('po');
			$table->string('size');
			$table->string('bagno')->nullable();
			$table->string('module');
			$table->string('leader');
			$table->integer('qty');

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
		Schema::drop('req_extrabbs');
	}

}
