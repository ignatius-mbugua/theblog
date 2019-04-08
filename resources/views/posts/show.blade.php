@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-9 col-md-8">
            <a href="/posts" class="btn btn-default">Go To Posts</a>
            <h1>{{$post->title}}</h1>
            <img style="width:100%" src="/storage/cover_images/{{$post->cover_image}}">
            <br><br>
            <div>
                {!!$post->body!!}
            </div>
            <hr>
            Tag(s):
                @foreach ($post->tags as $tag)
                    <label class="label label-info">{{$tag->name}}</label>
                @endforeach
            </hr>
            <hr>
            <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
            <hr>
            {{--  @if(!Auth::guest())
                @if(Auth::user()->id == $post->user_id)
                    <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>
    
                    {!!Form::open(['action'=>['PostsController@destroy', $post->id], 'method'=>'POST', 'class'=>'pull-right'])!!}
                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Delete', ['class'=>'btn btn-danger'])}}
                    {!!Form::close()!!}
                @endif
            @endif  --}}
        </div>
    
        <div class="well col-sm-3 col-md-4">
            <h2>Other Posts</h2>
            @php
                $show_id = $post->id
            @endphp
            @foreach ($posts as $post)
                @if ($show_id != $post->id)
                    <p><a href="/posts/{{$post->id}}">{{$post->title}}</a></p>
                @endif
       
            @endforeach
        </div>
        <div class="well col-sm-3 col-md-4">
            <h2>Categories</h2>
            @foreach ($all_view_categories as $category)
                <p><a href="/categories/{{$category->id}}">{{$category->category}}</a></p>
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-sm-9 col-md-8">
            @if (Auth::guest())
                <div><a class="btn btn-lg btn-danger" href="{{ route('login') }}">Log In to post a comment</a></div>
            @else
                {!! Form::open(['method'=>'POST', 'action'=>'CommentController@store']) !!}
                    <div class="form-group">
                        {{Form::textarea('user_comment','', ['class'=>'form-control', 'placeholder'=>'Leave a comment'])}}
                    </div>
                    {{Form::hidden('post_id', $show_id)}}
                    {{Form::submit('Comment', ['class'=>'btn btn-success'])}}
                {!! Form::close() !!}
            @endif

            <h2>Comments <span class="badge">{{$comments_count}}</span></h2>

            @if(count($comments) > 0)
                    @foreach($comments as $comment)
                        <p>
                            <b>{{ $comment->user->name }}</b><br>
                            {{ $comment->comment}}
                        </p>
                    @endforeach
            @else
                <p>No comments avaliable be the first to comment </p>
            @endif
        </div>
    </div>
    
@endsection