<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laptop extends Model
{
     use SoftDeletes;

        //nama table
    protected $table = 'laptops';

    //fillable
    protected $guarded = [];
    public function services()
    {
        return $this->hasMany(Services::class, 'laptop_id');
    }
     protected $dates = ['deleted_at'];
}
