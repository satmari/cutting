<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('marker_headers', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('marker_name')->unique();

			$table->float('marker_width');
			$table->float('marker_length');

			$table->string('marker_type')->nullable();
			$table->string('marker_code')->nullable();
			$table->string('fabric_type')->nullable();
			$table->string('constraint')->nullable();

			$table->float('spacing_around_pieces')->nullable();
			$table->float('spacing_around_pieces_top')->nullable();
			$table->float('spacing_around_pieces_bottom')->nullable();
			$table->float('spacing_around_pieces_right')->nullable();
			$table->float('spacing_around_pieces_left')->nullable();

			$table->datetime('processing_date')->nullable();
			
			$table->float('efficiency')->nullable();
			$table->float('cutting_perimeter')->nullable();
			$table->float('perimeter')->nullable();
			$table->float('average_consumption')->nullable();
			$table->float('lines')->nullable();
			$table->float('curves')->nullable();
			$table->float('areas')->nullable();
			$table->float('angles')->nullable();
			$table->float('notches')->nullable();
			$table->float('total_pcs')->nullable();

			$table->string('variant_model');
			$table->string('key');

			$table->float('min_length')->nullable();
			$table->string('status');

			$table->string('creation_type')->nullable();

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
		Schema::drop('marker_headers');
	}

}
