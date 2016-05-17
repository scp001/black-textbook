@extends('...basic')
@section('content')
<style>
            tr {
                cursor: pointer;
            }
            tr.new {
                background: rgba(0, 170, 255, 0.24);
            }
        </style>
<div role="document" class="wrap container">
    <div class="content row">
        <div role="main" class="main col-sm-12">
            <div class="page-header"><h1>Inbox</h1></div>
            <div class="page-main clearfix">
                <p>&nbsp;</p>
                {{$outPut}}

            </div>
        </div>
    </div>
</div>
@stop