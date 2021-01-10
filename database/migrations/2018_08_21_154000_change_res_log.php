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
		// Schema::table('res_logs', function($table)
		// {
    		
  //   		// $table->string('po_status')->nullable();
    		
    		
		// });
		Schema::table('req_reprintbbs', function($table)
		{
    		
    		// $table->string('size')->nullable();
    		
    		
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
