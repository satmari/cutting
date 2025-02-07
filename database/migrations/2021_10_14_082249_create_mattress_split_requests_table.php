<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressSplitRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattress_split_requests', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id_orig');
			$table->string('mattress_orig');
			$table->string('g_bin_orig');

			$table->string('marker_name_orig');
			$table->integer('marker_id_orig');
			$table->integer('marker_width');
			$table->float('marker_length');

			$table->integer('requested_width');
			$table->float('requested_length')->nullable();

			$table->string('comment_operator')->nullable();

			$table->string('status');

			$table->string('operator1');
			$table->string('location');

			$table->integer('mattress_id_new')->nullable();
			$table->string('mattress_new')->nullable();
			$table->integer('layers')->nullable();

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
		Schema::drop('mattress_split_requests');
	}

}
