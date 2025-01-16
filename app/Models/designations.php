<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designations extends Model
{
    protected $table = 'designation';
    function department(){
        return $this->hasOne('App\Models\departments','id','department_id');
    }
}
