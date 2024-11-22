<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboundDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inbound_deliveries', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('document_no');
			$table->string('posting_date');
			$table->string('material');
			$table->string('bagno');
			$table->float('qty_received_m');
			$table->string('preforigin');
			$table->string('reserve_status')->nullable();
			$table->string('type')->nullable();

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
		Schema::drop('inbound_deliveries');
	}

}
