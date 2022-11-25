<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $orderId = $request->cookie('orderId');

        if(is_null($orderId))
        {
            return response(['message' => 'Корзина пустая']);
        }

        $order = Order::find($orderId);

        return response($order->where('id', $orderId)->with('items')->first());
    }

    public function addToCart(Request $request, $itemId)
    {
        $orderId = $request->cookie('orderId');

        $item = Item::find($itemId);

        if($item == null)
        {
            return response(['Message' => "Товар с id=$itemId не найден"]);
        }

        if(is_null($orderId))
        {
            $order = Order::create();
            $cookie = cookie('orderId', $order->id);

            $order->items()->attach($item);

            return response($order->items())->withCookie($cookie);
        }

        $order = Order::find($orderId);

        if($order->items->contains($itemId))
        {
            $rowPivot = $order->items()->where('item_id', $itemId)->first()->pivot;
            $rowPivot->count++;
            $rowPivot->update();
        }
        else
        {
            $order->items()->attach($item);
        }

        $item->count--;
        $item->update();

        return response($order->where('id', $orderId)->with('items')->first());
    }

    public function removeAllFromCart(Request $request)
    {
        $orderId = $request->cookie('orderId');

        if(is_null($orderId))
        {
            return response(['message' => 'Корзина пустая']);
        }
        else
        {
            $order = Order::find($orderId);
            $items = $order->items;
            foreach ($items as $item)
            {
                $rowPivot = $order->items()->where('item_id', $item->id)->first()->pivot;
                $count = $rowPivot->count;

                $item->count+=$count;
                $item->update();

                $order->items()->detach($item);
            }
            return response($order->where('id', $orderId)->with('items')->first());
        }
    }

    public function removeFromCart(Request $request, $itemId)
    {
        $orderId = $request->cookie('orderId');

        $item = Item::find($itemId);

        if($item == null)
        {
            return response(['Message' => "Товар с id=$itemId не найден"]);
        }

        if(is_null($orderId))
        {
            return response(['message' => 'Корзина пустая']);
        }

        $order = Order::find($orderId);

        if($order->items->contains($itemId))
        {
            $rowPivot = $order->items()->where('item_id', $itemId)->first()->pivot;
            if($rowPivot->count < 2)
            {
                $order->items()->detach($item);
            }
            else
            {
                $rowPivot->count--;
                $rowPivot->update();
            }
        }

        $item->count++;
        $item->update();

        return response($order->where('id', $orderId)->with('items')->first());
    }
}
