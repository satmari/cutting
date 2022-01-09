<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressPhasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattress_phases', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id');
			$table->string('mattress');

			$table->string('status');
			$table->string('location');
			$table->string('device')->nullable();

			$table->boolean('active')->default(0);

			$table->string('operator1')->nullable();
			$table->string('operator2')->nullable();

			$table->dateTime('date')->nullable();

			$table->string('id_status')->nullable(); // added key in future

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
		Schema::drop('mattress_phases');
	}

}
