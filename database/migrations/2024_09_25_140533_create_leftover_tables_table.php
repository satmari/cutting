<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeftoverTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leftover_tables', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('skeda');
			$table->string('reservation_date');
			$table->string('document_no');
			$table->string('material');
			$table->string('bagno');
			$table->string('preforigin');
			$table->float('qty_reserved_m');
			$table->float('qty_leftover_m');
			$table->string('operator')->nullable();
			$table->string('comment')->nullable();
			$table->string('frez_id')->nullable();
			$table->string('inbd_id')->nullable();

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
		Schema::drop('leftover_tables');
	}

}
