<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintStandardMattressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('print_standard_mattresses', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('mattress');
			$table->string('marker_name')->nullable();
			$table->string('g_bin')->nullable();
			$table->string('location')->nullable();
			$table->string('skeda')->nullable();

			$table->string('overlapping')->nullable();
			$table->string('width_theor_usable')->nullable();
			$table->string('marker_length')->nullable();
			$table->string('spreading_method')->nullable();
			$table->string('material')->nullable();
			$table->string('color_desc')->nullable();
			$table->string('dye_lot')->nullable();
			$table->string('pcs_bundle')->nullable();
			$table->string('bottom_paper')->nullable();
			$table->string('tpp_mat_keep_wastage')->nullable();
			
			$table->string('layers')->nullable();
			$table->string('padprint_item')->nullable();
			$table->string('padprint_color')->nullable();
			$table->text('comment_office')->nullable();

			$table->string('printer')->nullable();

			$table->string('pro_0')->nullable();
			$table->string('style_size_0')->nullable();
			$table->string('pro_pcs_actual_0')->nullable();
			$table->string('destination_0')->nullable();

			$table->string('pro_1')->nullable();
			$table->string('style_size_1')->nullable();
			$table->string('pro_pcs_actual_1')->nullable();
			$table->string('destination_1')->nullable();

			$table->string('pro_2')->nullable();
			$table->string('style_size_2')->nullable();
			$table->string('pro_pcs_actual_2')->nullable();
			$table->string('destination_2')->nullable();

			$table->string('pro_3')->nullable();
			$table->string('style_size_3')->nullable();
			$table->string('pro_pcs_actual_3')->nullable();
			$table->string('destination_3')->nullable();

			$table->string('pro_4')->nullable();
			$table->string('style_size_4')->nullable();
			$table->string('pro_pcs_actual_4')->nullable();
			$table->string('destination_4')->nullable();

			$table->string('pro_5')->nullable();
			$table->string('style_size_5')->nullable();
			$table->string('pro_pcs_actual_5')->nullable();
			$table->string('destination_5')->nullable();

			$table->string('pro_6')->nullable();
			$table->string('style_size_6')->nullable();
			$table->string('pro_pcs_actual_6')->nullable();
			$table->string('destination_6')->nullable();

			$table->string('pro_7')->nullable();
			$table->string('style_size_7')->nullable();
			$table->string('pro_pcs_actual_7')->nullable();
			$table->string('destination_7')->nullable();

			$table->string('pro_8')->nullable();
			$table->string('style_size_8')->nullable();
			$table->string('pro_pcs_actual_8')->nullable();
			$table->string('destination_8')->nullable();

			$table->string('pro_9')->nullable();
			$table->string('style_size_9')->nullable();
			$table->string('pro_pcs_actual_9')->nullable();
			$table->string('destination_9')->nullable();

			$table->string('pro_10')->nullable();
			$table->string('style_size_10')->nullable();
			$table->string('pro_pcs_actual_10')->nullable();
			$table->string('destination_10')->nullable();

			$table->string('pro_11')->nullable();
			$table->string('style_size_11')->nullable();
			$table->string('pro_pcs_actual_11')->nullable();
			$table->string('destination_11')->nullable();
			
			$table->string('pro_12')->nullable();
			$table->string('style_size_12')->nullable();
			$table->string('pro_pcs_actual_12')->nullable();
			$table->string('destination_12')->nullable();

			$table->string('pro_13')->nullable();
			$table->string('style_size_13')->nullable();
			$table->string('pro_pcs_actual_13')->nullable();
			$table->string('destination_13')->nullable();

			$table->string('pro_14')->nullable();
			$table->string('style_size_14')->nullable();
			$table->string('pro_pcs_actual_14')->nullable();
			$table->string('destination_14')->nullable();

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
		Schema::drop('print_standard_mattresses');
	}

}
