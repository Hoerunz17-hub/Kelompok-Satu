<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usertiga extends Model
{
     use SoftDeletes;

       //nama table
    protected $table = 'users';

    //fillable
    protected $guarded = [];

    public function customerServices()
    {
        return $this->hasMany(Services::class, 'customer_id')->withTrashed();
    }

    public function technicianServices()
    {
        return $this->hasMany(Services::class, 'technician_id')->withTrashed();
    }
     protected $dates = ['deleted_at'];
}
