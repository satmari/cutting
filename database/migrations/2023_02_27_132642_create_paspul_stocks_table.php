<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_stocks', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('pas_key_location')->unique();
			$table->string('location');

			$table->string('pas_key');
			$table->string('pas_key_e');

			$table->string('skeda');
			$table->string('paspul_type');
			$table->string('dye_lot');
			$table->float('kotur_length');

			$table->integer('kotur_qty');
			$table->float('kotur_width')->nullable();
			$table->string('uom')->nullable();
			$table->string('material')->nullable();

			$table->string('fg_color_code')->nullable();

			$table->string('returned_from')->nullable();

			$table->float('pcs_kotur')->nullable();

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
		Schema::drop('paspul_stocks');
	}

}
