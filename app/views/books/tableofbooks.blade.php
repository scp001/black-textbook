<?php

if(!isset($currentUser)){
$currentUser = Auth::user();
}
?>
<table class="table">
        <thead>
        <th>
        #
        </th>
        <th style="max-width:50px">
        icon
        </th>
        <th>
                title
                </th>
                <th>
                        field
                        </th><th>
                        author
                        </th>
                        <th>
                        course
                        </th>
                        <th>
                        Date Added
                        </th>
                        @if(Auth::check() && Auth::user()->id == $currentUser->id)
                        <th class="col-sm-2">
                            Actions
                        </th>
                        @endif

        </thead>
        <tbody>
@if(count($books) > 0 )
        @foreach($books as $book)
        <tr>
<td>
{{$counter++;}}
</td>
<td>
@if($book->photo == "")
<img style="height:64px;width:64px" src="{{URL::asset('default-book.png')}}" alt="{{$book->title}}">
@else
<img style="height:64px;width:64px" src="{{URL::asset($book->photo)}}" alt="{{$book->title}}">
@endif
</td>
<td>
<a href="{{URL::to('books/show/'.$book->id.'/'.Bookasset::generate_seo_link($book->title))}}">
{{$book->title}}
</a>
</td>
<td>
{{$book->field}}
</td>
<td>
{{$book->author}}
</td>
<td>
{{$book->course}}
</td>
<td>
{{$book->created_at}}
</td>
<td>
@if(Auth::check() && Auth::user()->id == $book->user_id)
<a href="{{URL::to('books/edit/'.$book->id)}}" class="btn btn-primary">
Edit
</a>
<a href="{{URL::to('books/del/'.$book->id)}}" class="btn btn-danger">
Delete
</a>
@else
<a href="{{URL::to('inbox?action=send_mes&uid='.$book->user_id)}}" class="btn btn-primary">
Contact Owner
</a>
@endif
</td>
        </tr>
        @endforeach
        @endif
        </tbody>
        </table>