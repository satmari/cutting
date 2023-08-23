<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaspulStockUConsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paspul_stock_u_cons', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('skeda_paspul_type')->unique();
			$table->string('skeda');
			$table->string('paspul_type');
			$table->string('style');

   			$table->float('mtr_per_pcs')->nullable();

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
		Schema::drop('paspul_stock_u_cons');
	}

}
