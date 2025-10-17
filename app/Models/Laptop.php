<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
        //nama table
    protected $table = 'laptops';

    //fillable
    protected $guarded = [];
    public function services()
    {
        return $this->hasMany(Services::class, 'laptop_id');
    }
}
