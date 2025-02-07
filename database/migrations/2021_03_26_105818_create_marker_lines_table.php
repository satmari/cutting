<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('marker_lines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('marker_header_id');
			$table->string('marker_name');

			$table->string('style');
			$table->string('size');
			$table->string('style_size');
			$table->float('pcs_on_layer');
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
		Schema::drop('marker_lines');
	}

}
