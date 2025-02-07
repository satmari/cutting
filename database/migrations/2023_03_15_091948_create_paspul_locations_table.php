<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_locations', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('location'); 
			$table->string('type'); // stock, lines
			$table->string('plant'); // Subotica, Kikinda, Senta, Valy
			
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
		Schema::drop('paspul_locations');
	}

}
