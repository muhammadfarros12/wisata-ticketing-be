<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\SKU;
use App\Models\Ticket;
use App\Service\Midtrans\CreatePaymentUrlService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'order_details' => 'required|array',
            'order_details.*.sku_id' => 'required|exists:skus,id',
            'quantity' => 'required|integer|min:1',
            'event_date' => 'required'
        ]);

        // 1. check ticket availability by sku id
        foreach ($request->order_details as $orderDetail) {
            $sku = SKU::find($orderDetail['sku_id']); // carii SKU berdasarkan sku_id
            $qty = $orderDetail['quantity'];
            $tickets = Ticket::where('sku_id', $sku->id)
                ->where('status', 'available')
                ->get(); // ambil tiket yang tersedia berdasarkan sku_id

            if ($qty > $tickets->count()) { // jika jumlah tiket yang diminta lebih besar dari tiket yang tersedia
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket "' . $sku->name . '" is not available. Only ' . $tickets->count() . ' tickets left.'
                ], 400); // example: Ticket "VIP Ticket" is not available. Only 3 tickets left.
            }
        }

        // 2. total price
        $totalPrice = 0;
        foreach ($request->order_details as $orderDetail) {
            $sku = SKU::find($orderDetail['sku_id']);
            $totalPrice += $sku->price * $orderDetail['quantity'];
        }

        // 3. create order
        $order = Order::create([
            'user_id' => $request->user()->id,
            'event_id' => $request->event_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'event_date' => $request->event_date
        ]);

        // 4. create order details
        foreach ($request->order_details as $orderDetail) {
            $sku = SKU::find($orderDetail['sku_id']);
            $qty = $orderDetail['quantity'];

            for ($i = 0; $i < $qty; $i++) {
                $ticket = Ticket::where('sku_id', $sku->id)
                    ->where('status', 'available')
                    ->first();

                OrderDetail::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id
                ]);

                $ticket->update([
                    'status' => 'booked'
                ]);
            }

            // 5. midtrans
            $midtrans = new CreatePaymentUrlService();
            $user = $request->user();
            $order['user'] = $user;
            $order['orderItems'] = $request->order_details;
            $paymentUrl = $midtrans->getPaymentUrl($order);
            $order['payment_url'] = $paymentUrl;

            // return response
            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);


        }

    }
}
