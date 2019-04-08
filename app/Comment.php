<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //table name
    protected $table = 'comments';

    //primary key
    public $primarkey = 'id';

    //timestamps
    public $timestamps = true;

    //comment belongs to a post
    public function post(){
        return $this->belongsTo('App\Post');
    }

    //comment belongs to a user
    public function user(){
        return $this->belongsTo('App\User');
    }

}
