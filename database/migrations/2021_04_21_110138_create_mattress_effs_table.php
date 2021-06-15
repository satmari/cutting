<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattressEffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mattress_effs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('mattress_id')->unique();
			$table->string('mattress')->unique();

			$table->string('layers_after_cs');
			$table->string('operator_after');
			$table->string('layers_before_cs')->nullable();
			$table->string('operator_before')->nullable();

			$table->float('stimulation_after');
			$table->float('stimulation_before')->nullable();

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
		Schema::drop('mattress_effs');
	}

}
