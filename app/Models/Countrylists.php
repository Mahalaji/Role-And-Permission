<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countrylists extends Model
{
    protected $table = 'tbl_countries';
    protected $fillable = [
     'name'
    ];
}
