<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeResLog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('wastages', function($table)
		{
    		
  //   		$table->string('material')->nullable();
    		
    		
		// });
		// Schema::table('wastage_logs', function($table)
		// {
    		
  //   		$table->string('material')->nullable();
    		
   //  		$table->string('tpp_ship')->nullable();
			// $table->string('approval')->nullable();
			// $table->string('log_rep')->nullable();
			
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
