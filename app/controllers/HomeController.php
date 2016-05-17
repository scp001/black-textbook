<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}
    public function showLanding()
    {
        return View::make('landing');
    }
    public function printProfile($currentUser= false)
    {
        if (!Auth::check()){
            return Redirect::to('login');
        }
        $user = Input::get('pid');
        if (isset($user)){
            $currentUser=User::where('id','=',intval($user))->first();
        }else {
            $currentUser = Auth::user();
        }

        $counter=1;
        $books = DB::table('books')->where('user_id','=',$currentUser->id)->take(10)->get();
        $hoppies = DB::table('user_hoppies as uh')->where('uh.user_id','=',$currentUser->id)->join('hoppies as h','h.id','=','uh.hoppy_id')->take(10)->lists('h.title');
        $meta = DB::table('user_meta')->where('user_id','=',$currentUser->id)->get(['meta_key','meta_value']);
        $dataToShow = array ( 'profile_pic','frist_name', 'last_name', 'direct_phone', 'position', 'country', 'city', 'school', 'schooladdress', 'postal', 'address', 'tele');
        $data = [];
    foreach($meta as $row)
    {
        if(in_array($row->meta_key,$dataToShow)){
            $data[$row->meta_key] = $row->meta_value;
        }
    }

        return View::make('myprofile',compact('data','currentUser','counter','books','hoppies'));
    }
    public function printForget()
    {
        $fields= [
            ['type'=>'form_start',
                'method'=>'post',
                'action'=>URL::to('users/forgot_password')
            ],
            [
                'type'=>'text',
                'name'=>'email',
                'placeholder'=>'Email'
            ],
            [
                'type'=>'submit',
                'placeholder'=>'Continue',
                'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
            ],
            ['type'=>'form_end']
        ];
        $outPut = $this->drawForm($fields);
        return View::make('forgetpassowrd',compact('outPut'));
    }
    public function printLogin()
    {
        $fields= [
            ['type'=>'form_start',
            'method'=>'post',
            'action'=>URL::to('users/login')
            ],
            [
                'type'=>'text',
                'name'=>'email',
                'placeholder'=>'Username or Email '
            ],
            [
                'type'=>'password',
                'name'=>'password',
                'placeholder'=>'Password'

            ],
            [
                'type'=>'checkbox',
                'name'=>'remember',
                'value'=>'1',
                'placeholder'=>'Remember Me',
                'id'=>'remember'

            ],
            [
                'type'=>'submit',
                'placeholder'=>'Login',
                'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
            ],
            ['type'=>'form_end']
        ];
        $outPut = $this->drawForm($fields);
        return View::make('login',compact('outPut'));
    }
    public function printSignup()
    {
        $countries = DB::table('countries')->select('code as value','name as option')->get();


        $fields = [
            [
                'type'=>'form_start',
                'action'=>URL::to('users/create'),
                'method'=>'post'
            ],
            [
                'type'=>'text',
                'placeholder'=>'Username',
                'class'=>'',
                'id'=>'',
                'name'=>'username'
            ],
            [
                'type'=>'email',
                'placeholder'=>'Email',
                'class'=>'mail',
                'id'=>'reg-mail',
                'name'=>'email'
            ],
            [
                'type'=>'text',
                'placeholder'=>'First name',
                'class'=>'fname',
                'id'=>'fname',
                'name'=>'frist_name'
            ],
            [
                'type'=>'text',
                'placeholder'=>'Last name',
                'class'=>'lname',
                'id'=>'lname',
                'name'=>'last_name'
            ],
            [
                'type'=>'password',
                'placeholder'=>'Password',
                'class'=>'pw',
                'id'=>'pwinput',
                'name'=>'password'
            ],
            [
                'type'=>'password',
                'placeholder'=>'Confirm Password',
                'class'=>'pw2',
                'id'=>'pwinput2',
                'name'=>'password_confirmation'
            ],
            [
                'type'=>'heading',
                'placeholder'=>'Personal Information',
            ],
            [
                'type'=>'text',
                'placeholder'=>'Direct Phone',
                'class'=>'dphone',
                'id'=>'dphone',
                'name'=>'direct_phone'
            ],
            [
                'type'=>'text',
                'placeholder'=>'Position',
                'class'=>'position',
                'id'=>'position',
                'name'=>'position'
            ],
            [
                'type'=>'select',
                'placeholder'=>'Country',
                'class'=>'',
                'id'=>'',
                'data'=>$countries,
                'name'=>'country'
            ],
            [
                'type'=>'typeahead',
                'placeholder'=>'City',
                'class'=>'',
                'id'=>'city',
                'name'=>'city'
            ],
            [
                'type'=>'typeahead',
                'placeholder'=>'School/University',
                'class'=>'',
                'id'=>'school',
                'name'=>'school'
            ],
            [
                'type'=>'text',
                'placeholder'=>'School Street address',
                'class'=>'',
                'id'=>'',
                'name'=>'schooladdress'
            ],
            [
                'type'=>'text',
                'placeholder'=>'Postal Code',
                'class'=>'',
                'id'=>'',
                'name'=>'postal'
            ],
            [
                'type'=>'text',
                'placeholder'=>'Street address',
                'class'=>'',
                'id'=>'',
                'name'=>'address'
            ],
            [
                'type'=>'text',
                'placeholder'=>'Telephone',
                'class'=>'',
                'id'=>'',
                'name'=>'tele'
            ],
            [
                'type'=>'hidden',
                'name'=>'_token',
                'value'=>csrf_token()
            ],
            [
                'type'=>'submit',
                'placeholder'=>'Singup',
                'class'=>' btn-success col-sm-offset-4 col-sm-4 text-bold',
                'id'=>'',
                'name'=>''
            ]

        ];
        $outPut = $this->drawForm($fields);
        $tailScripts = [
            'typeahead.js'
        ];
        return View::make('signup',compact('outPut','tailScripts'));
    }

}
