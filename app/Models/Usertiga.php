<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usertiga extends Model
{
       //nama table
    protected $table = 'users';

    //fillable
    protected $guarded = [];

    public function customerServices()
    {
        return $this->hasMany(Services::class, 'customer_id');
    }

    public function technicianServices()
    {
        return $this->hasMany(Services::class, 'technician_id');
    }
}
