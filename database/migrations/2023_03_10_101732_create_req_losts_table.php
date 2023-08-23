<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqLostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('req_losts', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('sku');
			$table->integer('qty');
			$table->string('module');
			$table->string('bagno');
			$table->string('status');
			$table->string('comment');
			
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
		Schema::drop('req_losts');
	}

}
