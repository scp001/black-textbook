<?php

class BooksController extends BaseController {



    public function __construct()
    {
        $this->beforeFilter('auth');
        $this->user = Confide::user();

    }
    public $seachable = ['title','author','course','field','school','city'];
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



    public function anySearch()
    {
        $currentUser=Confide::user();
        $searchParams = $this->get_posted_data(Input::all());
        $fields = $fields= [
            ['type'=>'form_start',
                'method'=>'post',
                'action'=>URL::to('books/search')
            ],
            [
                'type'=>'text',
                'name'=>'title',
                'placeholder'=>'Book Title '
                ,'value'=>Input::get('title')
            ],
            [
                'type'=>'text',
                'name'=>'author',
                'placeholder'=>'Book author '
                ,'value'=>Input::get('author')
            ],
            [
                'type'=>'text',
                'name'=>'field',
                'placeholder'=>'Book field '
                ,'value'=>Input::get('field')
            ],
            [
                'type' => 'text',
                'name' => 'course',
                'placeholder' => 'Book course'
                , 'value' => Input::get('course')
            ],
            [
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


            ],
            [
                'type'=>'submit',
                'placeholder'=>'Search',
                'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
            ],
            ['type'=>'form_end']
        ];
        $searchForm = $this->drawForm($fields);

        $thebooks=DB::table('books');
        //dd($searchParams);
        if (count($searchParams) > 0)
        {
            foreach($searchParams as $k => $param){
                if ($k == 'school' || $k == 'city')
                {
                if ($k == 'school'){
                    $aimSchool = User::get_user_meta($currentUser->id,'school');
                    $userFromSameSchool=DB::table('user_meta')->where('meta_key','school')->where('meta_value',$aimSchool)->lists('user_id');
                    $thebooks->whereIn('user_id',$userFromSameSchool);
                }
                if($k == 'city'){
                    $aimCity = User::get_user_meta($currentUser->id,'city');
                    //dd($aimCity);
                    $userFromSameCity=DB::table('user_meta')->where('meta_key','city')->where('meta_value',$aimCity)->lists('user_id');
                    $thebooks->whereIn('user_id',$userFromSameCity);
                }

                } else
                {
                $thebooks->where($k,'like','%'.$param.'%');
                }

            }
        }


        $books=$thebooks->paginate(5);


        $counter= 1;
        return View::make('books.search',compact('books','counter','searchForm'));
    }
    public function getShow($id,$title=false)
    {

        $id = intval($id);
        $book = DB::table('books')->where('id','=',$id)->take(1)->first();
        $bookSeoTitle = Bookasset::generate_seo_link($book->title);
        if ($title == false || $title != $bookSeoTitle ){
            return Redirect::to('books/show/'.$id.'/'.$bookSeoTitle,302);
        }else {


            return View::make('books.single',compact('book'));
        }


    }


    public function postSave($id)
    {
        $id = intval($id);
        $theBook = DB::table('books')->where('id','=',$id)->first();
        if ($theBook->user_id == $this->user->id){
            $bookData =Input::all();
                $file = Input::file('photo');
            if (isset($file) && $file->isValid()) {
                //
                //======== Image Upload =======///
                $destinationPath = 'uploads/books/';
                $filename = str_random(12) . '-' . time(false);

                $extension = $file->getClientOriginalExtension();
                $upload_success = $file->move($destinationPath, $filename . '.' . $extension);
                if ($upload_success) {
                    $bookData['photo'] = $destinationPath . $filename . '.' . $extension;
                }
            }else{
                if (isset($bookData['removeoldimg']) && $bookData['removeoldimg'] == ''){
                    unset($bookData['removeoldimg']);
                    $bookData['photo'] = '';
                }
            }
            unset($bookData['_token']);
            $bookData['updated_at']=new Datetime();
            $validator = Validator::make(
                $bookData,
                array(
                    'title'=>'required:min5',
                    'author'=>'required:min3',
                    'field'=>'required:min3',
                    'course'=>'required:min3',
                )
            );

            if($validator->fails()){

                return Redirect::back()->with('error',$validator->messages()->first());
            }else{

            $action = DB::table('books')->where('id','=',$id)->update($bookData);

            return Redirect::to('/books/show/'.$id,301)->with('success','Book has been updated');

            }

        }else{

            return Redirect::back()->with('error','Something Went Wrong. #asdasd3ASA#$');
        }

    }
    public function getEdit($id)
    {
        $id = intval($id);
        $theBook = DB::table('books')->where('id','=',$id)->first();
        $fields= [
            ['type'=>'form_start',
                'method'=>'post',
                'action'=>URL::to('books/save/'.$theBook->id)
            ],
            [
                'type'=>'text',
                'name'=>'title',
                'placeholder'=>'Book Title ',
                'value'=>$theBook->title
            ],
            [
                'type'=>'text',
                'name'=>'author',
                'placeholder'=>'Book author ',
                'value'=>$theBook->author
            ],
            [
                'type'=>'text',
                'name'=>'field',
                'placeholder'=>'Book field ',
                'value'=>$theBook->field

            ],
            [
                'type'=>'text',
                'name'=>'course',
                'placeholder'=>'Book course ',
                'value'=>$theBook->course
            ],
            [
                'type'=>'old_pic',
                'img'=>URL::asset($theBook->photo),
                'placeholder'=>'Curremt Profile pic'
            ],
            [
                'type'=>'file',
                'name'=>'photo',
                'placeholder'=>'Book Photo',
                'help'=>'<i>Leave it blank if you don\'t want to change the photo</i>'
            ],
            [
                'type'=>'hidden',
                'name'=>'_token',
                'value'=>csrf_token()
            ],
            [
                'type'=>'submit',
                'placeholder'=>'Save',
                'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
            ],
            ['type'=>'form_end']
        ];

        $outPut = $this->drawForm($fields);
        return View::make('books.edit',compact('outPut'));

    }

    public function getDel($id)
    {
        $thebook = DB::table('books')->where('id','=',intval($id));
        $bookUser = $thebook->pluck('user_id');
        if ($bookUser == $this->user->id)
        {
            $thebook->delete();
            return Redirect::back()->with('success','The book has been removed ');
        }else {
            return Redirect::back()->with('error','SomeThing went wrong. ');
        }


    }
    public function postStore()
    {
        $bookData = Input::all();
        $bookData['user_id'] = $this->user->id;
        $validator = Validator::make(
            $bookData,
            array(
                'title'=>'required:min5',
                'author'=>'required:min3',
                'field'=>'required:min3',
                'course'=>'required:min3',
            )
        );
        if ($validator->fails())
        {
            return Redirect::back()->with('error',$validator->messages()->first());
        }else {
            $file = Input::file('photo');
            if (isset($file) && $file->isValid())
            {
                //
            //======== Image Upload =======///
            $destinationPath = 'uploads/books/';
            $filename = str_random(12) . '-' . time(false);

            $extension = $file->getClientOriginalExtension();
            $upload_success = $file->move($destinationPath, $filename . '.' . $extension);
            if($upload_success){
                $bookData['photo']=$destinationPath.$filename . '.' . $extension;
                Image::make(__DIR__.'/../../public/'.$bookData['photo'])->fit(540, 540)->save();
            }

            //======== Image Upload =======///
            }else {
                $bookData['photo']='';
            }
            unset($bookData['_token']);
            unset($bookData['removeoldimg']);
            $bookData['created_at']= new Datetime();
            $bookData['updated_at']= new Datetime();
            $action = DB::table('books')->insertGetId($bookData);


            if ($action){

                return Redirect::to('books/show/'.$action)->with('success','Your book has been added ');

            }else {

            return Redirect::back()->with('error','something went wrong #111n1j1j1j1basXFf');
            }
        }

    }
    public function getAdd()
    {
        $fields= [
            ['type'=>'form_start',
                'method'=>'post',
                'action'=>URL::to('books/store')
            ],
            [
                'type'=>'text',
                'name'=>'title',
                'placeholder'=>'Book Title '
            ],
            [
                'type'=>'text',
                'name'=>'author',
                'placeholder'=>'Book author '
            ],
            [
                'type'=>'text',
                'name'=>'field',
                'placeholder'=>'Book field '
            ],
            [
                'type'=>'text',
                'name'=>'course',
                'placeholder'=>'Book course '
            ],
            [
                'type'=>'old_pic',
                'img'=>URL::asset('default-book.png'),
                'placeholder'=>'Book image'
            ],
            [
                'type'=>'file',
                'name'=>'photo',
                'placeholder'=>'Book Photo'
            ],
            [
                'type'=>'hidden',
                'name'=>'_token',
                'value'=>csrf_token()
            ],
            [
                'type'=>'submit',
                'placeholder'=>'Add',
                'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
            ],
            ['type'=>'form_end']
        ];
        $outPut = $this->drawForm($fields);
        return View::make('books.add',compact('outPut'));
    }
    public function getIndex()
    {
        $counter=1;
        $books = DB::table('books')->where('user_id','=',$this->user->id)->paginate(5);
        return View::make('books.mybooks',compact('books','counter'));
    }




    public function getHome()
    {
        return 'this is the hoome for all books section of the site.';
    }
}
