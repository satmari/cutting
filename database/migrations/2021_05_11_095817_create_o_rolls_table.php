<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateORollsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('o_rolls', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('o_roll')->unique();

			$table->integer('mattress_id_orig');
			$table->string('mattress_name_orig');
			$table->string('g_bin');
			$table->string('material');
			$table->string('skeda');
			
			$table->integer('mattress_id_new')->nullable();
			$table->string('mattress_name_new')->nullable();

			$table->string('status');
			$table->integer('no_of_joinings');
			$table->string('operator')->nullable();

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
		Schema::drop('o_rolls');
	}

}
