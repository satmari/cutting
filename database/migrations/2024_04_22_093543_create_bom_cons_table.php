<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBomConsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bom_cons', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('order')->nullable();
			$table->string('style')->nullable();
			$table->string('material')->nullable();
			$table->string('father')->nullable();
			
			$table->float('main')->nullable();
			$table->float('pas_ag')->nullable();
			$table->float('ploce')->nullable();
			$table->float('total_cons')->nullable();
			
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
		Schema::drop('bom_cons');
	}

}
