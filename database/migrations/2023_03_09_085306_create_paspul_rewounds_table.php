<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulRewoundsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_rewounds', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('paspul_rewound_roll')->unique();
			$table->float('rewound_length_partialy')->nullable();
			$table->float('kotur_partialy')->nullable();
			$table->string('status');

			$table->integer('paspul_roll_id');
			$table->string('paspul_roll');
			$table->string('sap_su')->nullable();
			$table->string('material');
			$table->string('color_desc');
			$table->string('dye_lot');
			$table->string('paspul_type');

			$table->float('width');
			$table->float('kotur_width');
			$table->float('kotur_width_without_tension')->nullable();
			$table->float('kotur_planned');
			// $table->float('kotur_actual')->nullable();
			$table->float('rewound_length');
			//$table->float('rewound_length_a')->nullable();
			
			$table->string('pasbin');
			$table->string('skeda_item_type');
			$table->string('skeda');
			$table->string('skeda_status');
			$table->string('rewound_roll_unit_of_measure');
			$table->integer('position');
			$table->integer('priority');
			$table->string('comment_office')->nullable();
			$table->string('comment_operator')->nullable();
			$table->boolean('call_shift_manager')->default(0);

			$table->string('rewinding_method')->nullable();
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
		Schema::drop('paspul_rewounds');
	}

}
