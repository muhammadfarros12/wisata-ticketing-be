<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SKU extends Model
{
    protected $table = 'skus';
    protected $fillable = [
        'event_id',
        'name',
        'category',
        'price',
        'stock',
        'day_type'
    ];

    public function ticket(){
        return $this->hasMany(Ticket::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
