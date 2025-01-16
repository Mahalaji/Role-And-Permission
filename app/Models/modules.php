<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Modules extends Model
{
    use HasFactory;
    protected $table = 'module';
 
    public function permission()
    {
        return $this->hasMany(permissions::class,'module_id');
    }
    public function childmodule()
    {
        return $this->hasMany(modules::class,'parent_id');
    }
}

