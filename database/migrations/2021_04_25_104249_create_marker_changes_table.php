<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerChangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('marker_changes', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id');
			$table->string('mattress');

			$table->integer('marker_id_orig');
			$table->string('marker_name_orig');
			$table->float('marker_length_orig');
			$table->float('marker_width_orig');
			$table->float('min_length_orig');

			$table->integer('marker_id_new');
			$table->string('marker_name_new');
			$table->float('marker_length_new');
			$table->float('marker_width_new');
			$table->float('min_length_new');
			
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
		Schema::drop('marker_changes');
	}

}
