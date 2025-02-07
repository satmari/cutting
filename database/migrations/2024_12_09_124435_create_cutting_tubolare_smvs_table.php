<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuttingTubolareSmvsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cutting_tubolare_smvs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('material');
			$table->string('layers_group');
			$table->string('material_group');
			$table->float('average_min_per_meter');
			$table->float('average_min_per_layer');
			
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
		Schema::drop('cutting_tubolare_smvs');
	}

}
