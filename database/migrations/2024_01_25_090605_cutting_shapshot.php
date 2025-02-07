<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CuttingShapshot extends Migration {

	public function up()
	{
		//
		Schema::create('cutting_snapshot', function(Blueprint $table)
		{	
			$table->increments('id');
			
			$table->datetime('date_exported');

			$table->string('g_bin_in_h')->unique();

			$table->string('location');
			$table->string('position');
			$table->string('status');
			$table->string('g_bin')->nullable();
			$table->string('mattress');

			$table->float('marker_width');
			$table->float('marker_length');

			$table->string('priority');
			$table->string('comment_office');
			$table->string('comment_operator');

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
