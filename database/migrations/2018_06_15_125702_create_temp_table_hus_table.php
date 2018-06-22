<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempTableHusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_table_hus', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('hu');
			$table->string('father_hu')->nullable();
			$table->string('item')->nullable();
			$table->string('variant')->nullable();
			$table->string('status')->nullable();
			$table->double('balance')->nullable();
			$table->string('batch')->nullable();
			$table->string('document')->nullable();
			$table->string('bin')->nullable();
			$table->string('location')->nullable();

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
		Schema::drop('temp_table_hus');
	}

}



/*
[HU No_] as hu,
[Item No_] as item, 
		 [Variant Code] as variant, 
		 [Status] as status, 
		 [Balance] as balance, 
		 [Quantity] as qty, 
		 [Batch_Dye lot] as batch,
		 [Document No_] as inv
*/
