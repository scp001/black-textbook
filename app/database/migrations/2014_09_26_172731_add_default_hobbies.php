<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultHobbies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $hobbies = [
            'Fishing',
            'Coaching',
            'Billiards',
            'Homebrewing',
            'Collecting',
            'Target shooting',
            'Rocketry',
            'Fantasy sports',
            'Mentoring',
            'Football',
            'Reading',
            'Writing',
            'Drawing',
            'Running',
            'Cycling',
            'Snorkeling',
            'Online games',
            'fighting',
            'movies',
            'Gardening',
            'Camping',
            'Caving',
            'Play cards',
            'dance',
            'Cooking',
            'Craft'
        ];
        foreach($hobbies as $hoppy){
            DB::table('hoppies')->insert(
                [
                    'title'=>trim(strtolower($hoppy))
                ]
            );
        }
		//
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
