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
    		// $table->integer('position')->nullable();
    		// $table->dropColumn('position');
    		// $table->integer('pre_position')->nullable();
    		
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
			// $table->integer('num_of_cut_problems')->nullable();
			// $table->float('req_time')->nullable();

		});

		Schema::table('paspul_stocks', function($table)
		{			
			
			// $table->float('kotur_width')->nullable();
			// $table->string('uom')->nullable();
			// $table->string('material')->nullable();
			// $table->string('fg_color_code')->nullable();

			// $table->string('returned_from')->nullable();

			// $table->float('pcs_kotur')->nullable();

		});

		Schema::table('paspul_stock_logs', function($table)
		{			
			
			// $table->float('kotur_width')->nullable();
			// $table->string('uom')->nullable();
			// $table->string('material')->nullable();
			// $table->string('fg_color_code')->nullable();

			// $table->string('skeda')->nullable();
			// $table->string('paspul_type')->nullable();
			// $table->string('dye_lot')->nullable();
			// $table->float('kotur_length')->nullable();

			// $table->string('returned_from')->nullable();

			// $table->float('pcs_kotur')->nullable();

		});

		Schema::table('marker_headers', function($table)
		{
			// $table->string('creation_type')->nullable();
			
		});

		Schema::table('parts', function($table)
		{
			// $table->string('creation_type')->nullable();
			// $table->string('key_part')->nullable();
			// $table->string('key_part')->nullable(false)->unique()->change();
		});


		Schema::table('part_lines', function($table) 
		{
            // $table->renameColumn('comment', 'operator');
            // $table->string('operator')->nullable();
			// $table->string('device')->nullable();
			// $table->string('key_part_line')->nullable();
			// $table->string('key_part_line')->nullable(false)->unique()->change();
        });

        Schema::table('part_g_bin_statuses', function($table) 
		{
            
            // $table->string('operator')->nullable();
			// $table->string('device')->nullable();
        });

        Schema::table('print_mini_mattresses', function($table) 
		{
            // $table->string('spreading_profile_0')->nullable();
            // $table->string('spreading_profile_1')->nullable();
            
			
        });
        
        Schema::table('print_standard_mattresses', function($table) 
		{
            // $table->string('spreading_profile')->nullable();
            
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
