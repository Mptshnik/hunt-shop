<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart($itemId)
    {
        $orderId = session('orderId');
        if(is_null($orderId))
        {
            $orderId = Order::create()->id;
        }
    }
}
