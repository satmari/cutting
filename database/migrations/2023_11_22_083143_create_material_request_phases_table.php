<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestPhasesTable extends Migration {

	public function up()
	{
		Schema::create('material_request_phases', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('material_request_id');

			$table->string('status');
			$table->string('location');
			$table->string('device')->nullable();

			$table->boolean('active')->default(0);

			$table->string('operator1')->nullable();
			$table->string('operator2')->nullable();

			$table->string('id_status')->unique();

			$table->timestamps();
		});
	}

	public function down() {
		
		Schema::drop('material_request_phases');
	}

}
