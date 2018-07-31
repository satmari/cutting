<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeReservationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('reservations', function($table)
		{
    		
    		// $table->double('original_qty')->nullable();
    		// $table->string('hu')->unique();
    		// $table->unique('hu');


    		
		});
		Schema::table('temp_table_hus', function($table)
		{
    		
    		// $table->double('original_qty')->nullable();
    		// $table->string('hu')->unique();
    		// $table->unique('hu');


    		
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
