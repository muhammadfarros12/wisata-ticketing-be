<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'event_id',
        'sku_id',
        'ticket_code',
        'ticket_date',
        'status'
    ];

    public function orderDetail(){
        return $this->hasOne(OrderDetail::class);
        // return $this->belongsTo(OrderDetail::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function SKU()
    {
        return $this->belongsTo(SKU::class, 'sku_id');
    }
}
