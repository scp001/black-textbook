<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateUserMateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_meta', function(Blueprint $table)
		{
            $table->index('user_id');
            $table->index('meta_key');
			
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_meta', function(Blueprint $table)
		{
            $table->dropIndex('user_id');
            $table->dropIndex('meta_key');

		});
	}

}
