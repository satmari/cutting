<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_lines', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('paspul_roll_id');
			$table->string('paspul_roll');

			$table->string('status');
			$table->string('location');
			$table->string('device')->nullable();
			$table->boolean('active')->default(0);

			$table->string('operator1')->nullable();
			$table->string('operator2')->nullable();

			$table->dateTime('date')->nullable();

			$table->string('id_status')->nullable(); // added 

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('paspul_lines');
	}

}
