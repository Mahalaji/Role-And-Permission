<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news extends Model
{
    use HasFactory;
    protected $table = 'news';
    function categories(){
        return $this->hasOne('App\Models\newscategory','id','category_id');
    }
    function domain(){
        return $this->hasOne('App\Models\domains','id','domain_id');
    }
    function language(){
        return $this->hasOne('App\Models\languages','id','language_id');
    }
    function status(){
        return $this->hasOne('App\Models\statuss','id','status_id');
    }
}
