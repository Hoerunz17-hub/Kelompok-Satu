<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';
     protected $guarded = [];
     public function customer()
    {
        return $this->belongsTo(Usertiga::class, 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(Usertiga::class, 'technician_id');
    }

    public function laptop()
    {
        return $this->belongsTo(Laptop::class, 'laptop_id');
    }

    public function details()
    {
        return $this->hasMany(Servicedetail::class, 'service_id');
    }

    public function serviceItems()
{
    return $this->belongsToMany(ServiceItem::class, 'servicedetails', 'service_id', 'serviceitem_id')
                ->withPivot('price');
}

}
