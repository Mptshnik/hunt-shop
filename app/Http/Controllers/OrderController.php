<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderFormRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

class OrderController extends Controller
{
    public function confirm(CreateOrderFormRequest $request)
    {
        $data = $request->validated();

        $orderId = $request->cookie('orderId');
        $order = Order::find($orderId);

        if($order == null)
        {
            return response(['message' => "Заказа с id=$orderId не найден"]);
        }

        $order['client_id'] = $data['client_id'];
        $order['status'] = 1;
        $order['number'] = (string)random_int(10000000,99999999);
        $order['order_time'] = Carbon::now()->format('Y-m-d H:m:s');

        $order->save();

        $cookie = Cookie::forget('orderId');

        $invoice_data = [
            'order_id' => $orderId,
            'seller_id' => Seller::$SELLER_ID,
            'number' => (string)random_int(10000000,99999999),
            'date' => Carbon::now()->format('Y-m-d H:m:s')
        ];

        Invoice::create($invoice_data);

        return response([
            'order' => $order,
            'message' => 'Заказ оформлен'
        ])->withCookie($cookie);
    }

    public function getOne($id)
    {
        $order = Order::find($id);

        if($order == null)
        {
            return response(['message' => "Заказа с id=$id не найден"]);
        }

        return response($order->where('id', $id)->with('items')->with('client')->first());
    }

    public function getAll()
    {
        return response(Order::with('items')->with('client')->get());
    }
}
