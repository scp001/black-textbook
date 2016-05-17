@extends('basic')
@section('content')
<div role="document" class="wrap container">
    <div class="content row">
        <div role="main" class="main col-sm-12">
            <div class="page-header clearfix">
            <h1 class="col-md-10">User profile </h1>
            @if(Auth::check() && Auth::user()->id == $currentUser->id)
            <a href="{{URL::to('users/editprofile')}}" class="btn btn-primary">
            Edit Profile
            </a>
            @endif
            </div>
            <div class="page-main clearfix">
                <p>&nbsp;</p>
                <div class="clearfix">
                    <div class="col-md-3 text-center">

                        <br>
                        @if ($data['profile_pic'] == '')
                        <img class="" src="{{URL::asset('default_profile.png')}}">
                        @else
                        <img class="profilethunb" src="{{URL::asset($data['profile_pic'])}}">
                        @endif;
                        <br>
                        <strong>{{$currentUser->username}}</strong><br>
                        @if(Auth::check() && Auth::user()->id != $currentUser->id)
                        <a href="{{URL::to('inbox?action=send_mes&uid='.$currentUser->id)}}" class="btn btn-sm btn-primary">
                                    Send a message
                                    </a>
                                    @endif
                        <br>
                        <i class="fa fa-fw fa-user"></i> {{@$data['frist_name']. ' ' .@$data['last_name']}}
                        <br>
                        <i class="fa fa-fw fa-paper-plane"></i> {{$currentUser->email}}
                        <br>
                        <i class="fa fa-fw fa-globe"></i> {{@$data['country']. ' ' .@$data['city']}}
                        <br>
                        <i class="fa fa-fw fa-mortar-board"></i> {{@$data['school']. ' '}}
                        <br>

                    </div>
                    <div class="col-md-9">
                        <h3>Personal information</h3>
                        <i class="fa fa-fw fa-phone"></i> {{@$data['direct_phone']}}
                        <br>
                        <i class="fa fa-fw fa-legal"></i> {{@$data['position']}}
                        <br>
                        <i class="fa fa-fw fa-location-arrow"></i> {{@$data['address']}}
                        <br>
                        <span title="postal code">
                        <i class="fa fa-fw fa-code"></i> {{@$data['postal']}}
                        </span>
                        <br>
                        <i class="fa fa-fw fa-mobile-phone"></i> {{@$data['tele']}}
                        <br>
                        <div class="clearfix">
                        <h4 class="col-md-10">Hobbies</h4>
                        @if(Auth::check() && Auth::user()->id == $currentUser->id)
                            <a href="{{URL::to('hoppy/myhoppies')}}" class="btn btn-primary pull-right">Edit hobbies</a>
                        @endif

                        </div>
                        <hr>
                        @foreach($hoppies as $hoppy)
                        <p class="hoppyitem">
                        <a href="{{URL::to('hoppy/search?q='.urlencode($hoppy))}}">
                        {{$hoppy}}
                        </a>

                        </p>
                        @endforeach
                    </div>
                    </div>

                    <div class="">
                    <div class="col-md-12">
                    <h4>Books</h4>
                    @if(Auth::check() && Auth::user()->id == $currentUser->id)
                        <a href="{{URL::to('books/add')}}" class="btn btn-primary pull-right">Add a Book</a>
                    @endif
                    @include('books.tableofbooks')
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop