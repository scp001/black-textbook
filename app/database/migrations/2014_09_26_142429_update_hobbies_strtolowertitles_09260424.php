<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHobbiesStrtolowertitles09260424 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        $hobbies = DB::table('hoppies')->get();
        foreach($hobbies as $hobby)
        {
            DB::table('hoppies')->where('id','=',$hobby->id)->update(
                [
                    'title'=>trim(strtolower($hobby->title))
                ]
            );
        }
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
