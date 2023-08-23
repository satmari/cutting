<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulStockByKeysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_stock_by_keys', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('pas_key')->unique();
			$table->string('pas_key_e')->unique();

			$table->float('unit_cons')->nullable();
			$table->string('fg_color_code')->nullable();

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
		Schema::drop('paspul_stock_by_keys');
	}

}
