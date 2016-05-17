<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserMetaProfilePicNormalize extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        $dataToShow = ['profile_pic'];
        $usersids = DB::table('users')->lists('id');
        foreach($usersids as $id ){
            foreach($dataToShow as $line){
                $action = DB::table('user_meta')->where('user_id','=',$id)->where('meta_key', '=',$line )->count();
                if($action < 1 ){
                    DB::table('user_meta')->insert(
                        [
                            'user_id'=>$id,
                            'meta_key'=>$line,
                            'meta_value'=>'-',
                            'updated_at'=>new DateTime(),
                            'created_at'=>new DateTime(),
                        ]
                    );
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
