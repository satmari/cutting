<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattresses', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('mattress')->unique();
			
			$table->string('g_bin')->nullable();
			$table->string('material');
			$table->string('dye_lot');
			$table->string('color_desc')->nullable();
			$table->float('width_theor_usable')->nullable();

			$table->string('skeda');
			$table->string('skeda_item_type');
			$table->string('skeda_status');
			$table->string('spreading_method');

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
		Schema::drop('mattresses');
	}

}
