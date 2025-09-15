<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'vendor_id',
        'event_category_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'image',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function skus()
    {
        return $this->hasMany(SKU::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
