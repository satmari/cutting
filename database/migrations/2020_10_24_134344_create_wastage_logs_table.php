<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWastageLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wastage_logs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('no');
			$table->string('skeda');
			$table->string('sap_bin');
			
			$table->float('weight')->nullable();
			$table->string('coment')->nullable();

			$table->string('container_id')->nullable();
			$table->string('container')->nullable();

			$table->float('location_id')->nullable();
			$table->string('location')->nullable();

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
		Schema::drop('wastage_logs');
	}

}
