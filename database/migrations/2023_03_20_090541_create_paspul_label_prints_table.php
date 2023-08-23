<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulLabelPrintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_label_prints', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('pas_key');
			$table->string('pas_key_e');

			$table->string('skeda');
			$table->string('paspul_type');
			$table->string('dye_lot');
			$table->float('kotur_length');

			$table->integer('kotur_qty');
			$table->float('kotur_width')->nulable();
			
			$table->string('uom')->nullable();
			$table->string('material')->nullable();
			$table->string('fg_color_code')->nullable();

			$table->float('fg_qty');
			$table->integer('qty');

			$table->string('printer');

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
		Schema::drop('paspul_label_prints');
	}

}
