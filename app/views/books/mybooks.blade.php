@extends('basic')
@section('content')
<div class="hero-image" id="hero" style="position: relative; z-index: 0; background: none repeat scroll 0% 0% transparent;">
<div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 300px; width: 1425px; z-index: -999998; position: absolute;"><img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 1425px; height: 798px; max-height: none; max-width: none; z-index: -999999; left: 0px; top: -249px;" src="{{URL::asset('assets/img/hero.jpg')}}">
</div>
</div>
<div class="container">
    <div id="boxes">
        <div class="home-title">
            <h2>My Books</h2>
            <a href="{{URL::to('books/add')}}" class="btn btn-primary pull-right">Add a Book</a>
        </div>
        <div class="">
        <div class="col-md-12">
@include('books.tableofbooks')
{{$books->links()}}
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