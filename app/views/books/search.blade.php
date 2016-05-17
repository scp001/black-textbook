@extends('basic')
@section('content')
<div class="hero-image" id="hero" style="position: relative; z-index: 0; background: none repeat scroll 0% 0% transparent;">
<div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 300px; width: 1425px; z-index: -999998; position: absolute;"><img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 1425px; height: 798px; max-height: none; max-width: none; z-index: -999999; left: 0px; top: -249px;" src="{{URL::asset('assets/img/hero.jpg')}}">
</div>
</div>
<div class="container">
    <div id="boxes">
        <div class="home-title">
            <h2>Search books</h2>
        </div>
        <div class="container">
        <div class="row text-left">
            <div class="col-md-11">
                {{$searchForm}}
            </div>
            </div>
        </div>
        <div class="">
        <div class="col-md-12">
        @if(count($books) > 0)
            @include('books.tableofbooks')
            {{$books->links()}}
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