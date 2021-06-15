<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattress_details', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id')->unique();
			$table->string('mattress')->unique();

			$table->float('layers');
			$table->float('layers_a')->nullable();
			$table->float('length_usable');
			$table->float('cons_planned');
			$table->float('cons_actual');
			$table->float('extra');
			$table->float('pcs_bundle')->nullable();
			$table->float('layers_partial')->nullable();
			$table->integer('position');
			$table->integer('priority');

			$table->boolean('call_shift_manager')->default(0);
			$table->boolean('test_marker')->default(0);
			$table->boolean('tpp_mat_keep_wastage')->default(0);
			$table->boolean('printed_marker')->default(0);
			$table->boolean('mattress_packed')->default(0);
			$table->boolean('all_pro_for_main_plant')->default(0);
			
			$table->string('bottom_paper')->nullable();
			$table->string('layers_a_reasons')->nullable();
			$table->text('comment_office')->nullable();
			$table->text('comment_operator')->nullable();
			$table->integer('requested_width')->nullable();
			$table->string('minimattress_code')->nullable();
			$table->string('overlapping')->nullable();
			$table->string('tpa_number')->nullable();
			
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
		Schema::drop('mattress_details');
	}

}
