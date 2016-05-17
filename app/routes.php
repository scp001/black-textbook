<?php

//$img = Image::make(__DIR__.'/../public/uploads/books/EvpKJ3rfsAgr-1411560984.jpeg')->fit(128, 128)->save();

Route::controller('inbox', 'InboxController');
Route::controller('hoppy', 'HoppiesController');
//Route::any('inbox',['users'=>'InboxController']);
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('signup', 'HomeController@printSignup');
//dd(x);
Route::get('login', 'HomeController@printLogin');
Route::get('forget', 'HomeController@printForget');
Route::get('myprofile', 'HomeController@printProfile');
Route::get('mybooks', 'BooksController@getIndex');




//

// Confide routes
Route::get('users/create', 'UsersController@create');
Route::post('users', 'UsersController@store');
Route::get('users/login', 'UsersController@login');
Route::post('users/login', 'UsersController@doLogin');
Route::get('users/confirm/{code}', 'UsersController@confirm');
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');


Route::controller('users', 'UsersController');
Route::controller('books', 'BooksController');
Route::get('/', 'HomeController@showLanding');
Route::get('/beta', function(){
    //$users = User::find(1)->test();
    $users= DB::table('users')->select('id')->get();
    //$locations = [];
    $locations = DB::table('user_meta')
        ->select(DB::raw('user_id, GROUP_CONCAT(meta_value SEPARATOR \', \') as location '))
        ->where(function($query){
            $query->orWhere('meta_key','=','country');
            $query->orWhere('meta_key','=','city');
            $query->orWhere('meta_key','=','address');
        })
        ->groupBy('user_id')
        ->get();
    //$locations =  DB::statement('select from user_meta where meta_key = \'country\' or meta_value = \'city\' or meta_key = \'address\'  group by user_id');
    //dd($locations);
    foreach($locations as $place)
    {
        $data = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($place->location));
        if (isset(json_decode($data)->results[0]->geometry->location)){
            DB::table('user_location')->insert(
                [
                    'user_id'=>$place->user_id,
                    'lat'=>json_decode($data)->results[0]->geometry->location->lat,
                    'lng'=>json_decode($data)->results[0]->geometry->location->lng
                ]
            );

        }
    }
});

Route::get('/citylist.json',function(){
    $cities = DB::table('user_meta')->where('meta_key','city')->lists('meta_value');
    $cities = array_unique($cities);
    return Response::json($cities);
});
Route::get('/unilist.json',function(){
    $schools = DB::table('user_meta')->where('meta_key','school')->lists('meta_value');
    $schools = array_unique($schools);
    return Response::json($schools);
});

Route::get('/beta',function(){


});
Route::get('/destroydb',function(){
    $out ='';

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `books`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `users`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `countries`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `hoppies`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `messages`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `message_replies`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `migrations`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `password_reminders`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `user_hoppies`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `user_location`;"));
    }catch (\Exception $e){$out .=$e;}
    try{
        DB::statement(DB::raw("DROP TABLE IF EXISTS `user_meta`;"));
    }catch (\Exception $e){$out .=$e;}

    return $out;

});
