<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Post;

class CategoryController extends Controller
{
    //fetch all categories for navbar
    public function index(){

    }

    public function show($id){
        $category = Category::findOrFail($id);
        $posts_in_category = Post::where('category_id', $id)->paginate(10);
        $posts_count = Post::where('category_id', $id)->count();
        return view('category.show', ['category'=>$category, 'posts_in_category'=>$posts_in_category, 'posts_count'=>$posts_count]);
    }
}
