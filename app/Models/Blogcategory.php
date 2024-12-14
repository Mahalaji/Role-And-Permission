<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogcategory extends Model
{
    use HasFactory;
    protected $table = 'blog_category';
    // public $timestamps = false;

  public function blogs()
    {
        return $this->hasMany('App\Models\Blog', 'category_id', 'id');
    }
}
