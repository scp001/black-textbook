<?php



/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */
class UsersController extends BaseController
{

    private function getPostedData($postedData=[])
    {
        $expectedData = array (
            'username',
            'email',
            'frist_name',
            'last_name',
            'password',
            'password_confirmation',
            'direct_phone',
            'position',
            'country',
            'city',
            'school',
            'schooladdress',
            'postal',
            'address',
            'tele',
            '_token');
        $data=[];
        foreach ($expectedData as $row ):
            if (isset($postedData[$row])):
                $data[$row]=$postedData[$row];
            endif;
        endforeach;
        return $data;
    }
    public function postCreate()
    {
        $data =$this->getPostedData($_POST);
        $repo = App::make('UserRepository');
        $user = $repo->signup($data);
        unset($data['_token']);
        unset($data['password_confirmation']);
        unset($data['password']);
        unset($data['email']);
        unset($data['username']);
        $data['profile_pic']='';

        if ($user->id) {
            foreach ($data as $key=>$val ){
                DB::table('user_meta')->insert(['user_id'=>$user->id,'meta_key'=>trim($key),'meta_value'=>trim($val)]);
            }
            if (Config::get('confide::signup_email')) {
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }

            return Redirect::to('login')
                ->with('success', 'Please check your mail to verify your E-mail address');
        } else {
            $error = $user->errors()->all(':message');

            return Redirect::to('signup')
                ->withInput(Input::except('password'))
                ->with('error', $error);
        }
    }
    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function create()
    {
        return View::make(Config::get('confide::signup_form'));
    }



    public function postUpdateprofile()
    {
        $currentUser= Confide::user();
        $userData = Input::all();
        unset($userData['_token']);
        $validator = Validator::make(
            $userData,
            [
                'frist_name'=>'required:min3',
                'last_name'=>'',
                'direct_phone'=>'alpha_dash',
                'country'=>'required',
                'city'=>'required',
                'school'=>'required',
                'schooladdress'=>'required',
                'postal'=>'required',
                'address'=>'required',
            ]
        );
            $file = Input::file('profile_pic');
        if (isset($file) && $file->isValid()) {
            //
            //======== Image Upload =======///
            $destinationPath = 'uploads/profiles/';
            $filename = str_random(12) . '-' . time(false);

            $extension = $file->getClientOriginalExtension();
            $upload_success = $file->move($destinationPath, $filename . '.' . $extension);
            if ($upload_success) {
                $userData['profile_pic'] = $destinationPath . $filename . '.' . $extension;
                //dd(__DIR__.'/../public/'.$userData['profile_pic']);
                Image::make(__DIR__.'/../../public/'.$userData['profile_pic'])->fit(128, 128)->save();
            }
        }else{
            if (isset($userData['removeoldimg']) && $userData['removeoldimg'] == ''){
                $userData['profile_pic'] = '';
            }
        }
        if($validator->fails()){
            return Redirect::back()->with('error',$validator->messages()->first());
        }else{
            $currentUser->touch();
            //dd($userData);
            foreach($userData as $k => $v){
                DB::table('user_meta')->where('user_id','=',$currentUser->id)->where('meta_key','=',$k)->update(['meta_key'=>$k,'meta_value'=>$v]);

            }
            return Redirect::to('myprofile')->with('success','You profile has been updated');
        }

            return Redirect::to('myprofile')->with('success','You profile has been updated');

    }
    public function getEditprofile()
    {
        $currentUser= Confide::user();
        $userMeta = DB::table('user_meta')->where('user_id','=',$currentUser->id)->get(['meta_key','meta_value']);


        //===================================
        $meta = DB::table('user_meta')->where('user_id','=',$currentUser->id)->get(['meta_key','meta_value']);
        $dataToShow = array ( 'frist_name', 'last_name', 'direct_phone', 'position', 'country', 'city', 'school', 'schooladdress', 'postal', 'address', 'tele');
        $data = [];
        foreach($meta as $row)
        {
            $data[$row->meta_key] = $row->meta_value;
        }
        foreach($dataToShow as $line)
        {
            if(!isset($data[$line])){
                $data[$line] = '-';
            }
        }
        $countries = DB::table('countries')->select('code as value','name as option')->get();
        if ($data['profile_pic'] == '')
        {
            $data['profile_pic'] ='default_profile.png';
        }
        $fields = [
            [
                'type'=>'form_start',
                'action'=>URL::to('users/updateprofile'),
                'method'=>'post'
            ],


            [
                'type'=>'text',
                'placeholder'=>'First name',
                'class'=>'fname',
                'id'=>'fname',
                'name'=>'frist_name',
                'value'=>$data['frist_name']
            ],
            [
                'type'=>'text',
                'placeholder'=>'Last name',
                'class'=>'lname',
                'id'=>'lname',
                'name'=>'last_name'
                ,'value'=>$data['last_name']
            ],
            [
                'type'=>'text',
                'placeholder'=>'Direct Phone',
                'class'=>'dphone',
                'id'=>'dphone',
                'name'=>'direct_phone'
                ,'value'=>$data['direct_phone']
            ],
            [
                'type'=>'text',
                'placeholder'=>'Position',
                'class'=>'position',
                'id'=>'position',
                'name'=>'position'
                ,'value'=>$data['position']

            ],
            [
                'type'=>'select',
                'placeholder'=>'Country',
                'class'=>'',
                'data'=>$countries,
                'id'=>'',
                'name'=>'country'
                ,'value'=>$data['country']

            ],
            [
                'type'=>'typeahead',
                'placeholder'=>'City',
                'class'=>'',
                'id'=>'city',
                'name'=>'city'
                ,'value'=>$data['city']
            ],
            [
                'type'=>'typeahead',
                'placeholder'=>'School/University',
                'class'=>'',
                'id'=>'school',
                'name'=>'school'
                ,'value'=>$data['school']
            ],
            [
                'type'=>'text',
                'placeholder'=>'School Street address',
                'class'=>'',
                'id'=>'',
                'name'=>'schooladdress'
                ,'value'=>$data['schooladdress']
            ],
            [
                'type'=>'text',
                'placeholder'=>'Postal Code',
                'class'=>'',
                'id'=>'',
                'name'=>'postal'
                ,'value'=>$data['postal']
            ],
            [
                'type'=>'text',
                'placeholder'=>'Street address',
                'class'=>'',
                'id'=>'',
                'name'=>'address'
                ,'value'=>$data['address']
            ],
            [
                'type'=>'text',
                'placeholder'=>'Telephone',
                'class'=>'',
                'id'=>'',
                'name'=>'tele'
                ,'value'=>$data['tele']
            ],
            [
                'type'=>'old_pic',
                'img'=>URL::asset($data['profile_pic']),
                'placeholder'=>'Curremt Profile pic'
            ],
            [
                'type'=>'file',
                'name'=>'profile_pic',
                'placeholder'=>'Profile Pic'
            ],
            [
                'type'=>'hidden',
                'name'=>'_token',
                'value'=>csrf_token()
            ],
            [
                'type'=>'submit',
                'placeholder'=>'Update profile',
                'class'=>' btn-success col-sm-offset-4 col-sm-4 text-bold',
                'id'=>'',
                'name'=>''
            ]

        ];
        $outPut = $this->drawForm($fields);
        $tailScripts = [
            'typeahead.js'
        ];
        //=================================
        return View::make('editprofile',compact('currentUser','outPut','tailScripts'));
    }

    /**
     * Stores new account
     *
     * @return  Illuminate\Http\Response
     */
    public function store()
    {
        $repo = App::make('UserRepository');
        $user = $repo->signup(Input::all());

        if ($user->id) {

            if (Config::get('confide::signup_email')) {
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }

            return Redirect::to('login')
                ->with('notice', Lang::get('confide::confide.alerts.account_created'));
        } else {
            $error = $user->errors()->all(':message');

            return Redirect::to('signup')
                ->withInput(Input::except('password'))
                ->with('error', $error);
        }
    }

    /**
     * Displays the login form
     *
     * @return  Illuminate\Http\Response
     */
    public function login()
    {
        if (Confide::user()) {
            return Redirect::to('/');
        } else {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     * @return  Illuminate\Http\Response
     */
    public function doLogin()
    {
        $repo = App::make('UserRepository');
        $input = Input::all();

        if ($repo->login($input)) {
            return Redirect::intended('/');
        } else {
            if ($repo->isThrottled($input)) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ($repo->existsButNotConfirmed($input)) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return Redirect::to('login')
                ->withInput(Input::except('password'))
                ->with('error', $err_msg);
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string $code
     *
     * @return  Illuminate\Http\Response
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
            return Redirect::to('login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return Redirect::to('login')
                ->with('error', $error_msg);
        }
    }

    /**
     * Displays the forgot password form
     *
     * @return  Illuminate\Http\Response
     */
    public function forgotPassword()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     * @return  Illuminate\Http\Response
     */
    public function doForgotPassword()
    {
        if (Confide::forgotPassword(Input::get('email'))) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::to('login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::to('forget')
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Shows the change password form with the given token
     *
     * @param  string $token
     *
     * @return  Illuminate\Http\Response
     */
    public function resetPassword($token)
    {
        return View::make(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    public function doResetPassword()
    {
        $repo = App::make('UserRepository');
        $input = array(
            'token'                 =>Input::get('token'),
            'password'              =>Input::get('password'),
            'password_confirmation' =>Input::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if ($repo->resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::to('login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('UsersController@resetPassword', array('token'=>$input['token']))
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return  Illuminate\Http\Response
     */
    public function logout()
    {
        Confide::logout();

        return Redirect::to('/');
    }
}
