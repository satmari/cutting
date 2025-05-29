<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		
		Schema::create('material_requests', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('material');
			$table->string('dye_lot');
			
			$table->string('sap_location1')->nullable();
			$table->string('sap_location2')->nullable();
			$table->string('sap_location3')->nullable();
			$table->string('sap_location4')->nullable();
			$table->string('sap_location5')->nullable();
			$table->string('sap_location6')->nullable();
			
			$table->float('required_qty')->nullable();
			$table->mediumText('comment')->nullable();

			$table->mediumText('comment_wh')->nullable();
			
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
		Schema::drop('material_requests');
	}

}
