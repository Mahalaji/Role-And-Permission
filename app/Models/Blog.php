<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;


class Blog extends Model
{
    use HasFactory;
    use HasRoles;

    protected $table = 'blogs';

    function categories(){
        return $this->hasOne('App\Models\Blogcategory','id','category_id');
    }
    function domain(){
        return $this->hasOne('App\Models\domains','id','domain_id');
    }
    function language(){
        return $this->hasOne('App\Models\languages','id','language_id');
    }
}

