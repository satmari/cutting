<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressMarkersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattress_markers', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id')->unique();
			$table->string('mattress')->unique();

			$table->integer('marker_id');
			$table->string('marker_name');
			$table->string('marker_name_orig')->nullable();
			$table->float('marker_length');
			$table->float('marker_width');
			$table->float('min_length')->nullable();

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
		Schema::drop('mattress_markers');
	}

}
