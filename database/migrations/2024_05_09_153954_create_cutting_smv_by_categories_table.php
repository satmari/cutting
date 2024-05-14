<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuttingSmvByCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cutting_smv_by_categories', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('spreading_method');
			$table->string('material');
			$table->string('layers_group');
			$table->string('length_group');
			$table->float('average_of_min_per_meter_minm');

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
		Schema::drop('cutting_smv_by_categories');
	}

}
