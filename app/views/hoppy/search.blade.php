@extends('basic')
@section('content')
<div class="hero-image" id="hero" style="position: relative; z-index: 0; background: none repeat scroll 0% 0% transparent;">
<div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 300px; width: 1425px; z-index: -999998; position: absolute;"><img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 1425px; height: 798px; max-height: none; max-width: none; z-index: -999999; left: 0px; top: -249px;" src="{{URL::asset('assets/img/hero.jpg')}}">
</div>
</div>
<div class="container">
    <div id="boxes" class="clearfix">
        <div class="home-title">
            <h2>Search hobby</h2>
        </div>
        <div class="row">
        <div class="col-md-11">
            {{$searchForm}}
        </div>
        </div>
        <div class="">
        <div class="col-md-12">
        <div class="row">
        @if(count($users) > 0)
                @foreach($users as $user )
                    <div class="col-md-3" style="margin-bottom: 10px;">
                    <a href="{{URL::to('myprofile?pid='.$user['id'])}}">
                        @if($user['profile_pic'] == '')
                            <img class="profilethunb" src="{{URL::asset('default_profile.png')}}">
                        @else
                            <img class="profilethunb" src="{{URL::asset($user['profile_pic'])}}">
                        @endif
                        <p>
                            {{$user['frist_name']}} {{$user['last_name']}}
                        </p>
                    </a>
                    <a class=" btn btn-sm btn-primary" href="{{URL::to('inbox?action=send_mes&uid='.$user['id'])}}">
                    Contact Me
                    </a>


                    </div>
                @endforeach
                </div>
                </div>
                    <div class="row">
                    {{$user_ids->appends(array('q' => Input::get('q')))->links()}}
                    </div>
                @else
                    <h4 >Ops .... no result for that , try to do another search</h4>
                @endif

        </div>
        </div>
        </div>
    </div>
</div>
@stop

<style>
td {
text-align:left;
}
</style>