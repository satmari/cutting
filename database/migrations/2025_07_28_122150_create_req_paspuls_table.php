<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqPaspulsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('req_paspuls', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('module');
			$table->string('po');
			$table->string('bb');
			$table->string('style')->nullable();
			$table->string('color')->nullable();
			$table->string('size')->nullable();
			$table->string('bagno')->nullable();
			// $table->string('image');

			$table->string('part');
			$table->integer('qty');
			$table->string('comment')->nullable();
			$table->string('status')->nullable();

			$table->string('sent')->nullable();
            $table->integer('req_qty')->nullable();

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
		Schema::drop('req_paspuls');
	}

}
