@extends('basic')
@section('content')
<div role="document" class="wrap container">
    <div class="content row">
        <div role="main" class="main col-sm-12">
            <div class="page-header"><h1>My Hobbeies</h1></div>
            <div class="page-main clearfix">
                <p>&nbsp;</p>
                <div id="hoppycont">
                @foreach($hoppies as $hoppy)
                <span class="hoppyitem">{{$hoppy->title}}<span class="delhoppy" data-hid="{{$hoppy->id}}"><i class="fa fa-fw fa-trash-o"></i></span></span>
                @endforeach

                </div>

                {{$outPut}}

            <div class="row text-center">
            <span id="addhoppies" class="btn btn-primary">Add Hobbies <i class="fa fa-fw fa-arrow-down"></i></span>
            <br>
            <div id="hoppiesform" style="display:none; padding:20px">
            <form>
            <input type="text" id="hoppytxt">
            <button id="addbtn" class="btn btn-success btn-sm">Add <i class="fa fa-fw fa-plus"></i></button>
            </form>

            </div>

            </div>
            </div>
        </div>
    </div>
</div>
@stop