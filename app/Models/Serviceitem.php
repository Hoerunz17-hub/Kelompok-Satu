<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serviceitem extends Model
{
        //nama table
    protected $table = 'serviceitems';

    //fillable
    protected $guarded = [];
      public function details()
    {
        return $this->hasMany(Servicedetail::class, 'serviceitem_id');
    }
}
