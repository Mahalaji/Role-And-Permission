<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class companyaddress extends Model
{
    protected $table = 'companyaddress';
    protected $fillable = [
        'company_id',
        'address',
        'mobile',
        'latitude',
        'longitude',
    ];
}
