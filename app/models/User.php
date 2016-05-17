<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;

class User extends Eloquent implements ConfideUserInterface
{
    use ConfideUser;

    public static function get_user_meta($user_id, $meta_key)
    {

        $meta = DB::table('user_meta')->where('user_id','=',$user_id)
            ->where('meta_key','=',$meta_key)
            ->pluck('meta_value');
        return $meta;
    }
    public function test(){
        return $this->test='as';
    }

}