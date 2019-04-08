<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $title = "TheBlog";
        return view('pages.index', ['title'=>$title]);
    }

}
