<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartGBinStatusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('part_g_bin_statuses', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('g_bin');
			$table->string('status');
			$table->string('comment')->nullable();

			$table->string('operator')->nullable();
			$table->string('device')->nullable();


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
		Schema::drop('part_g_bin_statuses');
	}

}
