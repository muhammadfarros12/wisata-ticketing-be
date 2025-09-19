<?php

namespace App\Observers;

use App\Models\SKU;
use App\Models\Ticket;
use Str;

class SkuObserver
{
    /**
     * Handle the SKU "created" event.
     */
    public function created(SKU $sku): void
    {
        $prefix = strtoupper(substr($sku->name, 0, 3)); // ambil 3 huruf pertama

        for ($i = 0; $i < $sku->stock; $i++) {
            $code = $prefix . "-" . strtoupper(Str::random(6));

            Ticket::create([
                'event_id' => $sku->event_id,
                'sku_id' => $sku->id,
                'ticket_code' => $code,
                'status' => 'available'
            ]);
        }

    }

    /**
     * Handle the SKU "updated" event.
     */
    public function updated(SKU $sKU): void
    {
        //
    }

    /**
     * Handle the SKU "deleted" event.
     */
    public function deleted(SKU $sKU): void
    {
        //
    }

    /**
     * Handle the SKU "restored" event.
     */
    public function restored(SKU $sKU): void
    {
        //
    }

    /**
     * Handle the SKU "force deleted" event.
     */
    public function forceDeleted(SKU $sKU): void
    {
        //
    }
}
