<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspuls', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('paspul_roll')->unique();
			$table->string('sap_su')->nullable();
			$table->string('material');
			$table->string('color_desc');
			$table->string('dye_lot');
			$table->string('paspul_type');

			$table->float('width');
			$table->float('kotur_width');
			$table->float('kotur_width_without_tension')->nullable();
			$table->float('kotur_planned');
			$table->float('kotur_actual')->nullable();
			$table->float('rewound_length');
			$table->float('rewound_length_a')->nullable();

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

			$table->float('rewound_length_p')->nullable();
			
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
		Schema::drop('paspuls');
	}

}
