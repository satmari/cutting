<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateORollPrintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('o_roll_prints', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->string('o_roll');
			$table->string('printer');
			$table->integer('printed');

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
		Schema::drop('o_roll_prints');
	}

}
