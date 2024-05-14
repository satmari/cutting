<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintMiniMattressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('print_mini_mattresses', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('mattress_0');
			$table->string('marker_name_0')->nullable();
			$table->string('location_0')->nullable();
			$table->string('skeda_0')->nullable();

			$table->string('width_theor_usable_0')->nullable();
			$table->string('marker_length_0')->nullable();
			$table->string('marker_width_0')->nullable();
			$table->string('min_length_0')->nullable();
			$table->string('spreading_method_0')->nullable();
			$table->string('spreading_profile_0')->nullable();
			$table->string('material_0')->nullable();
			$table->string('color_desc_0')->nullable();
			$table->string('dye_lot_0')->nullable();
			$table->string('pcs_bundle_0')->nullable();
			$table->string('bottom_paper_0')->nullable();
			$table->string('tpp_mat_keep_wastage_0')->nullable();
			$table->string('layer_limit_0')->nullable();
			
			$table->string('layers_0')->nullable();
			$table->string('padprint_item_0')->nullable();
			$table->string('padprint_color_0')->nullable();
			$table->text('comment_office_0')->nullable();

			$table->string('o_roll_0_0')->nullable();
			$table->string('o_roll_1_0')->nullable();
			$table->string('o_roll_2_0')->nullable();
			$table->string('o_roll_3_0')->nullable();
			$table->string('o_roll_4_0')->nullable();
			$table->string('o_roll_5_0')->nullable();
			$table->string('o_roll_6_0')->nullable();
			$table->string('o_roll_7_0')->nullable();
			$table->string('o_roll_8_0')->nullable();
			$table->string('o_roll_9_0')->nullable();

			$table->string('pro_0')->nullable();
			$table->string('style_size_0')->nullable();
			$table->string('destination_0')->nullable();
			$table->string('pro_pcs_layer_0')->nullable();
			$table->string('multimaterial_0')->nullable();

			// 2
			$table->string('mattress_1')->nullable();
			$table->string('marker_name_1')->nullable();
			$table->string('location_1')->nullable();
			$table->string('skeda_1')->nullable();

			$table->string('width_theor_usable_1')->nullable();
			$table->string('marker_length_1')->nullable();
			$table->string('min_length_1')->nullable();
			$table->string('marker_width_1')->nullable();
			$table->string('spreading_method_1')->nullable();
			$table->string('spreading_profile_1')->nullable();
			$table->string('material_1')->nullable();
			$table->string('color_desc_1')->nullable();
			$table->string('dye_lot_1')->nullable();
			$table->string('pcs_bundle_1')->nullable();
			$table->string('bottom_paper_1')->nullable();
			$table->string('tpp_mat_keep_wastage_1')->nullable();
			$table->string('layer_limit_1')->nullable();
			
			$table->string('layers_1')->nullable();
			$table->string('padprint_item_1')->nullable();
			$table->string('padprint_color_1')->nullable();
			$table->text('comment_office_1')->nullable();

			$table->string('o_roll_0_1')->nullable();
			$table->string('o_roll_1_1')->nullable();
			$table->string('o_roll_2_1')->nullable();
			$table->string('o_roll_3_1')->nullable();
			$table->string('o_roll_4_1')->nullable();
			$table->string('o_roll_5_1')->nullable();
			$table->string('o_roll_6_1')->nullable();
			$table->string('o_roll_7_1')->nullable();
			$table->string('o_roll_8_1')->nullable();
			$table->string('o_roll_9_1')->nullable();

			$table->string('pro_1')->nullable();
			$table->string('style_size_1')->nullable();
			$table->string('destination_1')->nullable();
			$table->string('pro_pcs_layer_1')->nullable();
			$table->string('multimaterial_1')->nullable();
			//2

			$table->string('printer')->nullable();
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
		Schema::drop('print_mini_mattresses');
	}

}
