@extends('basic')
@section('content')
<div role="document" class="wrap container">
    <div class="content row">
        <div role="main" class="main col-sm-12">
            <div class="page-header"><h1>Login</h1></div>
            <div class="page-main clearfix">
                <p>&nbsp;</p>
                {{$outPut}}
                <div class="row">

                <p class="pull-right">
                    <a href="{{URL::to('forget')}}">
                    <small>
                        Forget your password?
                    </small>
                    </a>
                </p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop