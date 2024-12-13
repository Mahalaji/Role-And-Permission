<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Blog extends Model
{
    use HasFactory;
    protected $table = 'blog';
    public $timestamps = false;
    function categories(){
        return $this->hasOne('App\Models\Blogcategory','id','blog_title_category');
    }
 
}

