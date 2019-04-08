@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    {!! Form::open(['action' => 'PostsController@store', 'method'=>'POST', 'enctype'=>'multipart/form-data'])!!}
        <div class="form-group">
            {{Form::label('category', 'Category')}}
            {{Form::select('categories', $categories, null, ['class' => 'form-control', 'placeholder'=>'Choose Category...'])}}
        </div>
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
            {{Form::label('body', 'Body')}}
            {{Form::textarea('body', '', ['id'=>'article-ckeditor', 'class'=>'form-control', 'placeholder'=>'Body Text'])}}
        </div>
        <div class="form-group">
            {{Form::file('cover_image')}}
        </div>
        <div>
            {{Form::label('tags', 'Tags')}}
            {{Form::text('tags', '', ['data-role' => 'tagsinput'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection