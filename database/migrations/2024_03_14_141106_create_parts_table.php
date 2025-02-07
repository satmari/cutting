<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parts', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('part');

			$table->string('g_bin');
			$table->string('style');
			$table->string('size');
			$table->string('bundle');
			
			$table->float('length_mode')->nullable();
			$table->float('width_mode')->nullable();

			$table->string('comment')->nullable();

			$table->string('key_part');

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
		Schema::drop('parts');
	}

}
