<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Post;
use App\Comment;
use App\Category;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //$posts = Post::orderBy('created_at', 'desc')->take(1)->get();//to fetch one item
        //$posts = Post::orderBy('created_at', 'desc')->get();
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
        return view('posts.index', ['posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::pluck('category', 'id')->toArray();
        return view('posts.create', ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'categories'=>'required',
            'title'=>'required',
            'body'=>'required',
            'cover_image'=>'image|mimes:jpeg,png,jpg|nullable|max:2048',
            'tags'=>'required'
        ]);

        //Handle file upload
        if($request->hasFile('cover_image')){
            //get filename with extension
            $file_name_with_ext = $request->file('cover_image')->getClientOriginalName();
            //get the filename
            $filename = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
            //get the file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //file name to store
            $file_name_to_store = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $file_name_to_store);

            if(Storage::exists('public/cover_thumbnails')){
                $img = Image::make($request->file('cover_image')->getRealPath())->resize(900,300)->save(storage_path('app/public/cover_thumbnails/'.$file_name_to_store), 75);
            }
            else{
                Storage::makeDirectory('public/cover_thumbnails');
                $img = Image::make($request->file('cover_image')->getRealPath())->resize(900,300)->save(storage_path('app/public/cover_thumbnails/'.$file_name_to_store), 75);
            }
        }
        else{
            $file_name_to_store = 'noimage.jpg';
        }

        //Save post in database
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $file_name_to_store;
        $post->category_id = $request->input('categories');
        $post->save();

        $tags = explode(",", $request->input('tags'));
        $post->tag($tags);

        return redirect('/home')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::findOrFail($id);
        $posts = Post::orderBy('created_at', 'desc')->take(5)->get();
        $comments = Comment::where('post_id', $id)->get();
        $comments_count = Comment::where('post_id', $id)->count();
        return view('posts.show', ['post'=>$post, 'posts'=>$posts, 'comments'=>$comments, 'comments_count'=>$comments_count]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id);
        //check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return view('posts.edit', ['post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validation
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required',
            'cover_image'=>'image|nullable|max:1999'
        ]);

        //Handle file upload
        if($request->hasFile('cover_image')){
            //get filename with extension
            $file_name_with_ext = $request->file('cover_image')->getClientOriginalName();
            //get the filename
            $filename = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
            //get the file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //file name to store
            $file_name_to_store = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $file_name_to_store);

            if(Storage::exists('public/cover_thumbnails')){
                $img = Image::make($request->file('cover_image')->getRealPath())->resize(900,300)->save(storage_path('app/public/cover_thumbnails/'.$file_name_to_store), 75);
            }
            else{
                Storage::makeDirectory('public/cover_thumbnails');
                $img = Image::make($request->file('cover_image')->getRealPath())->resize(900,300)->save(storage_path('app/public/cover_thumbnails/'.$file_name_to_store), 75);
            }
        }

        //Save post in database
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            if($post->cover_image != 'noimage.jpg'){
                //Delete Image
                Storage::delete('public/cover_images/'.$post->cover_image);
                //delete the thumbnail
                Storage::delete('public/cover_thumbnails'.$post->cover_image);
            }
            $post->cover_image = $file_name_to_store;
        }
        $post->save();
        return redirect('/posts/'.$id)->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        //check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        if($post->cover_image != 'noimage.jpg'){
            //Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
            //delete thumbnail
            Storage::delete('public/cover_thumbnails'.$post->cover_image);
        }

        $post->delete();
        return redirect('/home')->with('success', 'Post Removed');
    }
}
