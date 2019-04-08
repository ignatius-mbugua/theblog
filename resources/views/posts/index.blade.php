@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-9 col-md-8">
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    <h2>
                        <a href="/posts/{{$post->id}}">{{$post->title}}</a>
                    </h2>
                    <p>by: {{$post->user->name}}</p>
                    <p>Posted on: {{$post->created_at}}</p>
                    <hr>
                    <img class="img-responsive" width="900" height="300" src="/storage/cover_thumbnails/{{$post->cover_image}}">
                    <hr>
                    <p>{!!str_limit($post->body, 250)!!}</p>
                    <a class="btn btn-primary" href="/posts/{{$post->id}}">Read More</a>
                    <hr>
                @endforeach
            {{$posts->links()}}
            @else
                <p>No posts available</p>
            @endif
        </div>

        <div class="well col-sm-3 col-md-4">
            <h2>Categories</h2>
            @foreach ($all_view_categories as $category)
                <p><a href="/categories/{{$category->id}}">{{$category->category}}</a></p>
            @endforeach
        </div>
    </div>
    
@endsection
