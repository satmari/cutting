<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('part_lines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('part_id');
			$table->string('part');

			$table->string('g_bin');
			$table->string('style');
			$table->string('size');
			$table->string('bundle');
			
			$table->integer('layer');

			$table->float('length')->nullable();
			$table->float('width')->nullable();

			$table->string('operator')->nullable();
			$table->string('device')->nullable();

			$table->string('key_part_line');


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
		Schema::drop('part_lines');
	}

}
