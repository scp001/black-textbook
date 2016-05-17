<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserMetaTableAgain extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// reset dashes on profile pics
        DB::table('user_meta')
            ->where('meta_key','=','profile_pic')
            ->where('meta_value','=','-')
            ->update(['meta_value'=>'']);

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
