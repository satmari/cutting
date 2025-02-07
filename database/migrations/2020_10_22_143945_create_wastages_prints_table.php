<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWastagesPrintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wastages_prints', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('no');
			$table->string('skeda');
			$table->string('bin');
			
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
		Schema::drop('wastages_prints');
	}

}
