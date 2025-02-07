<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressProsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattress_pros', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id');
			$table->string('mattress');

			$table->string('style_size');

			$table->string('pro_id');
			$table->float('pro_pcs_layer');
			$table->float('pro_pcs_planned');
			$table->float('pro_pcs_actual');

			$table->integer('damaged_pcs')->nullable(); //added later
			
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
		Schema::drop('mattress_pros');
	}

}
