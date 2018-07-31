<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservations', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('hu')->unique();
			$table->string('father_hu')->nullable();
			$table->string('item')->nullable();
			$table->string('variant')->nullable();
			$table->string('status')->nullable();
			$table->double('balance')->nullable();
			$table->string('batch')->nullable();
			$table->string('document')->nullable();
			$table->string('bin')->nullable();
			$table->string('location')->nullable();

			$table->string('res_po')->nullable();
			$table->string('res_log_id')->nullable();
			$table->dateTime('res_date')->nullable();
			$table->string('res_status')->nullable();

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
		Schema::drop('reservations');
	}

}
