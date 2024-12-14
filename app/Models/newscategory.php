<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class newscategory extends Model
{
    use HasFactory;
    protected $table = 'newscategory';
    public function news()
    {
        return $this->hasMany('App\Models\news', 'category_id', 'id');
    }
}
