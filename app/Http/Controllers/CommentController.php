<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    //middleware auth
    public function __construct(){
        $this->middleware('auth');
    }

    //store comments to database
    public function store(Request $request){

        //check if comment has been written
        $this->validate($request, [
            'user_comment'=>'required'
        ]);

        //store the comment
        $comment = new Comment;
        $comment->comment = $request->input('user_comment');
        $comment->user_id = auth()->user()->id;
        $comment->post_id = $request->input('post_id');
        $comment->save();
        return redirect()->back()->with('success', 'Comment added to the post');
    }
}
