<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MassiveAssignHobbiesToUsers extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $users = DB::table('users')->get(['id']);
        $hobbies = DB::table('hoppies')->lists('id');
        foreach ($users as $user) {
            $counter = 0;
            while ($counter < 6) {
                $aimhobby = array_rand($hobbies);
                if (DB::table('user_hoppies')->where('user_id', '=', $user->id)->where('hoppy_id', '=', $hobbies[$aimhobby])->count() <= 0) {
                    DB::table('user_hoppies')->insert(
                        [
                            'user_id' => $user->id,
                            'hoppy_id' => $hobbies[$aimhobby]
                        ]
                    );
                    $counter++;
                }

            }
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
