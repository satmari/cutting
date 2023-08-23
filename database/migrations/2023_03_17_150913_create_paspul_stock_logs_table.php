<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulStockLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_stock_logs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('pas_key');
			$table->string('pas_key_e');

			$table->string('location_from');
			$table->string('location_to');
			$table->string('location_type');
		
			$table->integer('kotur_qty'); // partialy
			$table->string('operator')->nullable();
			$table->string('shift')->nullable();

			$table->float('kotur_width')->nullable();
			$table->string('uom')->nullable();
			$table->string('material')->nullable();
			$table->string('fg_color_code')->nullable();

			$table->string('skeda')->nullable();
			$table->string('paspul_type')->nullable();
			$table->string('dye_lot')->nullable();
			$table->float('kotur_length')->nullable();

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
		Schema::drop('paspul_stock_logs');
	}

}
