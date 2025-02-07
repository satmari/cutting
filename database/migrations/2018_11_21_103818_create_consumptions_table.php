<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consumptions', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('po')->unique();	//once
			$table->string('status');
			$table->string('to_be_finished')->nullable();
			$table->string('cut_prod_line')->nullable();
			$table->integer('order_qty'); //once

			$table->string('main_item'); //once
			$table->string('main_variant'); //once
			$table->double('qty_per')->nullable(); //once

			$table->double('teo_cons')->nullable(); //once
			$table->double('teo_cons_eur')->nullable(); //once

			$table->double('over_cons')->nullable();
			$table->double('over_cons_eur')->nullable();
			$table->double('percentage')->nullable();

			$table->string('extra_item')->nullable();
			$table->string('extra_variant')->nullable();
			$table->double('extra_consumed')->nullable();
			$table->double('extra_consumed_eur')->nullable();

			$table->string('error')->nullable();

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
		Schema::drop('consumptions');
	}

}
