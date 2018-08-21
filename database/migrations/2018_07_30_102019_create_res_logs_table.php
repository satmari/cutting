<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('res_logs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('res_po');
			$table->string('item');
			$table->string('variant');
			$table->string('batch');

			$table->double('res_qty');
			$table->integer('res_hus');

			$table->string('po_status')->nullable();
						
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
		Schema::drop('res_logs');
	}

}
