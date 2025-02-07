<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConsumptionSap1 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('consumption_sap_1', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('g_bin');
			$table->string('order');
			$table->string('material');
			$table->float('cons_real');

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
		//
	}

}
