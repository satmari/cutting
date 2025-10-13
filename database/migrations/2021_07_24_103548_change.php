<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// use Illuminate\Support\Facades\Schema;

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

    		// First remove nullable
            // $table->string('id_status')->nullable(false)->change();

            // Then, add the unique constraint
            // $table->unique('id_status');

    		
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
    		// $table->float('pcs_on_layer')->change(); // Change column to FLOAT

		});

		Schema::table('mattress_details', function($table)
		{			
			// $table->dropColumn('damaged_pcs');
			// $table->integer('num_of_cut_problems')->nullable();
			// $table->float('req_time')->nullable();
			// $table->string('mandatory_to_ins')->nullable();
			// $table->dropColumn('requested_length');
			// $table->float('requested_length')->nullable();

			// $table->float('cutter_shrink_x')->nullable();
			// $table->float('cutter_shrink_y')->nullable();

			// $table->boolean('last_mattress')->default(0);

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

		Schema::table('marker_lines', function($table)
		{
			// $table->string('key_part')->integer(false)->float(true)->change();
			 // $table->float('pcs_on_layer')->change(); // Change column to FLOAT
			
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

        Schema::table('inbound_deliveries', function($table) 
		{
            // $table->string('reserve_status')->nullable();
            // $table->dropColumn('reserve_status');
            // $table->string('type')->nullable();
            
        });

        Schema::table('paspuls', function($table) 
		{
            // $table->float('rewound_length_p')->nullable();
            
        });
        
        Schema::table('paspul_lines', function($table) 
		{
            // $table->string('id_status')->nullable(); 

            // First remove nullable
            // $table->string('id_status')->nullable(false)->change();

            // Then, add the unique constraint
            // $table->unique('id_status');
			// OR
            // ALTER TABLE [cutting].[dbo].[paspul_lines]
			// ADD CONSTRAINT UQ_id_status UNIQUE ([id_status]);
            
        });

        Schema::table('material_requests', function($table) 
		{
            // $table->mediumText('comment_wh')->nullable(); 
            
        });

        Schema::table('req_cut_parts', function($table) 
		{
            // $table->string('sent')->nullable();
            // $table->integer('req_qty')->nullable();
            
        });

        Schema::table('skeda_ratio_imports', function($table) 
		{
   			// $table->float('size_2_3y')->nullable();
			// $table->float('size_4_5y')->nullable();
			// $table->float('size_6_7y')->nullable();
			// $table->float('size_8_9y')->nullable();
			// $table->float('size_10_11y')->nullable();
			// $table->float('size_12_13y')->nullable();
            
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
