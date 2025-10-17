<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicedetail extends Model
{
  protected $table  = 'servicedetails';
  protected $guarded = [];

  public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function serviceItem()
    {
        return $this->belongsTo(Serviceitem::class, 'serviceitem_id');
    }
}
