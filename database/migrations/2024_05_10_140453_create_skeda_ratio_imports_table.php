<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkedaRatioImportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('skeda_ratio_imports', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('skeda')->nullable();
			
			$table->float('size_xs')->nullable();
			$table->float('size_s')->nullable();
			$table->float('size_m')->nullable();
			$table->float('size_l')->nullable();
			$table->float('size_xl')->nullable();
			$table->float('size_xxl')->nullable();
			$table->float('size_sm')->nullable();
			$table->float('size_ml')->nullable();
			$table->float('size_xssho')->nullable();
			$table->float('size_ssho')->nullable();
			$table->float('size_msho')->nullable();
			$table->float('size_lsho')->nullable();
			$table->float('size_tu')->nullable();
			$table->float('size_2y')->nullable();
			$table->float('size_3_4y')->nullable();
			$table->float('size_5_6y')->nullable();
			$table->float('size_7_8y')->nullable();
			$table->float('size_9_10y')->nullable();
			$table->float('size_11_12y')->nullable();
			$table->float('size_2')->nullable();
			$table->float('size_3')->nullable();
			$table->float('size_4')->nullable();
			$table->float('size_5')->nullable();
			$table->float('size_6')->nullable();
			$table->float('size_12')->nullable();
			$table->float('size_34')->nullable();

			$table->float('size_2_3y')->nullable();
			$table->float('size_4_5y')->nullable();
			$table->float('size_6_7y')->nullable();
			$table->float('size_8_9y')->nullable();
			$table->float('size_10_11y')->nullable();
			$table->float('size_12_13y')->nullable();
			
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
		Schema::drop('skeda_ratio_imports');
	}

}
