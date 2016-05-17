<?php

class HoppiesController extends BaseController {



    public function __construct(){
        $this->beforeFilter('auth');
        $this->current_user = Auth::user();
    }
    public function getMyhoppies(){

        $hoppies = DB::table('user_hoppies as uh')->select(['uh.id','h.title'])->where('user_id','=',$this->current_user->id)->join('hoppies as h','h.id','=','uh.hoppy_id')->get();

        $outPut='';

        return View::make('hoppy.myhoppies',compact('outPut','hoppies'));
    }
    public function getDel(){
        $hoppy = intval(urldecode(Input::get('q')));
        $action= DB::table('user_hoppies')->where('user_id','=',$this->current_user->id)->where('id','=',$hoppy)->delete();
        if ($action){
            return Response::json(true);
        }else{
            return Response::json(false);
        }

    }
    public function getAdd(){
        $hoppy = strtolower(trim(urldecode(Input::get('q'))));
        $validator = Validator::make(
            ['hoppy'=>$hoppy],
            ['hoppy'=>'required:min2']
        );
        if($validator->fails())
        {
            return Response::json(['failed'=>$validator->messages()->first()]);
        }
        $finded = DB::table('hoppies')->where('title','=',$hoppy)->first(['id']);
        //TODO :: remove user_hoppies timestamps
        //TODO:: remove hoppies timestamps
        if ($finded){
            $trytoget = DB::table('user_hoppies')
                    ->where('user_id','=',$this->current_user->id)
                ->where('hoppy_id','=',$finded->id)->count();
            if($trytoget < 1){
                $userhoppy = DB::table('user_hoppies')->insertGetId([

                        'user_id'=>$this->current_user->id,
                        'hoppy_id'=>$finded->id

                ]);
            return Response::json(['id'=>$userhoppy,'title'=>$hoppy]);
            }
            return Response::json(false);

        }else{
            $addedHoppy = DB::table('hoppies')->insertGetId(
                [
                    'title'=>$hoppy
                ]
            );
            $addedHoppyuser = DB::table('user_hoppies')->insertGetId(
                [
                    'user_id'=>$this->current_user->id,
                    'hoppy_id'=>$addedHoppy
                ]
            );
            return Response::json(['id'=>$addedHoppyuser,'title'=>$hoppy]);

        }



    }

    public $seachable = ['q'];
    private function get_posted_data($data){
        $newdata = [];
        foreach($this->seachable as $row )
        {
            if(isset($data[$row]))
            {
                $newdata[$row] = $data[$row];
            }
        }
        return $newdata;
    }

    public function getSearch(){
        $searchParams = $this->get_posted_data(Input::all());
        $fields = $fields= [
            ['type'=>'form_start',
                'method'=>'get',
                'action'=>URL::to('hoppy/search')
            ],
            [
                'type'=>'text',
                'name'=>'q',
                'placeholder'=>'Hobby '
                ,'value'=>Input::get('title')
            ],
            /*[
                'type'=>'checkbox',
                'name'=>'school',
                'value'=>'1',
                'class'=>'',
                'placeholder'=>'Same School/University',
                'id'=>'school',
                'checked' => Input::get('school')==1 ?'checked':''

            ],
            [
                'type'=>'checkbox',
                'name'=>'city',
                'value'=>'1',
                'class'=>'',
                'placeholder'=>'Same City',
                'id'=>'city',
                'checked' => Input::get('city')==1 ?'checked':''


            ],*/
            [
                'type'=>'submit',
                'placeholder'=>'Search',
                'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
            ],
            ['type'=>'form_end']
        ];
        $searchForm = $this->drawForm($fields);
        $data= strip_tags(trim(urldecode(Input::get('q'))));
        //$distance = intval(strip_tags(trim(urldecode(Input::get('d',10000)))));
        //$distance = $distance == 0 ? 100000:$distance;
        //$currentUserLocation=DB::table('user_location')->where('user_id','=',$this->current_user->id)->first(['lat','lng']);

/*        $users_ids_with_locations = DB::select(DB::raw('SELECT user_id,
        ( 3959 * acos( cos( radians(:lat1) ) * cos( radians( lat ) )
        * cos( radians( lng ) - radians(:lng) ) + sin( radians(:lat) )
        * sin( radians( lat ) ) ) ) AS distance
        FROM user_location HAVING distance < 25000
        ORDER BY distance LIMIT 10;'),['lat'=>$currentUserLocation->lat,'lat1'=>$currentUserLocation->lat,'lng'=>$currentUserLocation->lng]);*/
        //dd($users_ids_with_locations);
        $currentUser  = Confide::user();
        if(isset($searchParams['city'])){
            $aimCity = User::get_user_meta($currentUser->id,'school');
            $userFromSameCity=DB::table('user_meta')->where('meta_key','city')->where('meta_value',$aimCity)->lists('user_id');
        }
        if(isset($searchParams['school'])){
            $aimSchool = User::get_user_meta($currentUser->id,'school');
            $userFromSameSchool=DB::table('user_meta')->where('meta_key','school')->where('meta_value',$aimSchool)->lists('user_id');
        }
        $user_ids = DB::table('hoppies as h')
            ->select(['user_id'])
            ->join('user_hoppies as uh','uh.hoppy_id','=','h.id')
            ->where('title','like','%'.$data.'%')
            ->groupBy('uh.user_id')->paginate(5);;

/*
 *
 *
SELECT ul.user_id,
 ( 3959 * acos( cos( radians(37) ) * cos( radians( ul.lat ) )
  * cos( radians( ul.lng ) - radians(31) ) + sin( radians(37) )
  * sin( radians( ul.lat ) ) ) ) AS distance
FROM user_location ul
join user_hoppies uh on uh.user_id = ul.user_id
join hoppies h on h.id = uh.hoppy_id
where h.title like '%a%'
group by uh.user_id
HAVING distance < 25000 ORDER BY distance   LIMIT 10;

 * */

        $users= [];
        foreach($user_ids as $i => $user)
        {
            $userdata = DB::table('user_meta')->select(['meta_key','meta_value','user_id'])->where('user_id','=',$user->user_id)->where(function($query){
                $query->where('meta_key','=','frist_name')
                ->orwhere('meta_key','last_name')
                ->orwhere('meta_key','=','profile_pic');

            })->get();
//dd($userdata);
            foreach($userdata as $row){
                $users[$i]['id']=$row->user_id;
                $users[$i][$row->meta_key]=$row->meta_value;
            }
        }

       return View::make('hoppy.search',compact('users','searchForm','user_ids'));
    }
}
