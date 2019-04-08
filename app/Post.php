<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //tagging
    use \Conner\Tagging\Taggable;
    
    //Telling eloquent:
    //table name
    protected $table = 'posts';
    //primary key
    public $primarykey = 'id';
    //allow eloquent to manage timestamps
    public $timestamps = true;

    //post belongs to a user
    public function user(){
        return $this->belongsTo('App\User');
    }

    //post has many comments
    public function comments(){
        return $this->hasMany('App\Comment');
    }

    //post belongs to a category
    public function category(){
        return $this->belongsTo('App\Category');
    }
}
