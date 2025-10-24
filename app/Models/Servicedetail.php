<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicedetail extends Model
{
    use SoftDeletes;
  protected $table  = 'servicedetails';
  protected $guarded = [];

  public function service()
    {
        return $this->belongsTo(Services::class, 'service_id')->withTrashed();
    }

    public function serviceItem()
    {
        return $this->belongsTo(Serviceitem::class, 'serviceitem_id')->withTrashed();
    }
}
