<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SKU;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string',
            'category' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'day_type' => 'nullable|string'
        ]);

        $sku = SKU::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'SKU created successfully, tickets generated',
            'data' => [
                'sku' => $sku,
                'tickets_generated' => $sku->stock
            ]
        ], 201);
    }
}
