@extends('basic')
@section('content')
<div class="hero-image" id="hero" style="position: relative; z-index: 0; background: none repeat scroll 0% 0% transparent;">
<div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 300px; width: 1425px; z-index: -999998; position: absolute;"><img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 1425px; height: 798px; max-height: none; max-width: none; z-index: -999999; left: 0px; top: -249px;" src="{{URL::asset('assets/img/hero.jpg')}}">
</div>
</div>
<div class="container">
    <div id="boxes">
        <div class="home-title">
            <h2> {{$book->title}}</h2>
        </div>

        <div class="row">
                <div class="col-md-12">
                <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                @if($book->photo != '')
                <img class="" src="{{URL::asset($book->photo)}}" alt="{{$book->title}}">
                @endif
                </div>
                </div>
                <p>

                <strong>Book Author : </strong>{{$book->author}}
                </p>
                <p>
                <strong>Book Field : </strong>{{$book->field}}
                </p>
        <p>
        <strong>
        Book Course :
        </strong>
        {{$book->course}}
        </p>
        <p>
        <strong>
        Added At :
        </strong>
            {{$book->created_at}}
        </p>
                @if (Auth::user()->id != $book->user_id)
                <a href="{{URL::to('inbox?action=send_mes&uid='.$book->user_id)}}" class="btn btn-primary">
                Contact Owner
                </a>
                @else
                <a href="{{URL::to('books/edit/'.$book->id)}}" class="btn btn-primary">
                Edit
                </a>
                <a href="{{URL::to('books/del/'.$book->id)}}" class="btn btn-danger">
                Delete
                </a>
                @endif
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