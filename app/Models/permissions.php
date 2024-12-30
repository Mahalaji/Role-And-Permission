<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permissions extends Model
{
    protected $table = 'permissions';
    use HasFactory;
   public function module()
    {
        return $this->belongsTo(modules::class,'parent_id');
    }
}