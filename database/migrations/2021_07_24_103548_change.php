<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Change extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		//
		Schema::table('pro_skedas', function($table)
		{
    		
    		// $table->string('multimaterial')->nullable();
    		
		});

		Schema::table('mattress_phases', function($table)
		{
    		
    		// $table->dateTime('date')->nullable();
    		// $table->string('id_status')->nullable();
    		
		});

		Schema::table('mattress_effs', function($table)
		{
    		
    		// $table->dropColumn(['locoation_before']);
    		// $table->dropColumn('locoation_before');
    		// $table->dateTime('date_after')->nullable();
    		// $table->string('location_after')->nullable();

    		// $table->dateTime('date_before')->nullable();
    		// $table->string('location_before')->nullable();

    		// $table->string('operator2_after')->nullable();
    		// $table->string('operator2_before')->nullable();
    		
    		// $table->dateTime('date')->nullable();
		});

		Schema::table('operators', function($table)
		{
    		
    		// $table->string('operator')->unique();
    		// $table->unique('operator');
    		
		});

		Schema::table('mattress_pros', function($table)
		{
    		
    		// $table->dateTime('date')->nullable();
    		// $table->integer('damaged_pcs')->nullable(); //added later
		});

		Schema::table('mattress_details', function($table)
		{			
			// $table->dropColumn('damaged_pcs');

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
