<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProSkedasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pro_skedas', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('pro_id')->unique();
			$table->string('pro');
			$table->string('skeda');
			
			$table->string('padprint_item')->nullable();
			$table->string('padprint_color')->nullable();

			$table->string('style');
			$table->string('size');
			$table->string('style_size');
			$table->string('sku')->nullable();
			$table->string('multimaterial')->nullable();

			$table->float('bom_cons_per_pcs')->nullable();
			
			$table->float('bom_cons_per_pcs_a')->nullable();
			$table->float('extra_mat_a')->nullable();

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
		Schema::drop('pro_skedas');
	}

}
