<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serviceitem extends Model
{
    use SoftDeletes;

        //nama table
    protected $table = 'serviceitems';

    //fillable
    protected $guarded = [];
      public function details()
    {
        return $this->hasMany(Servicedetail::class, 'serviceitem_id');
    }
     protected $dates = ['deleted_at'];
}
