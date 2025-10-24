<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
     use SoftDeletes;

    protected $table = 'services';
     protected $guarded = [];
    public function customer()
{
    return $this->belongsTo(Usertiga::class, 'customer_id')->withTrashed();
}

public function technician()
{
    return $this->belongsTo(Usertiga::class, 'technician_id')->withTrashed();
}


    public function laptop()
    {
        return $this->belongsTo(Laptop::class, 'laptop_id')->withTrashed();
    }
    public function details()
    {
        return $this->hasMany(Servicedetail::class, 'service_id')->withTrashed();
    }

    public function serviceItems()
{
    return $this->belongsToMany(ServiceItem::class, 'servicedetails', 'service_id', 'serviceitem_id')
                ->withPivot('price')
                ->withTrashed();
}
 protected $dates = ['deleted_at'];

}
