<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderFormRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(CreateOrderFormRequest $request)
    {
        $data = $request->validated();
        $data['number'] = random_int(10000000,99999999);

        $order = Order::create($data);

        return response([
            'order' => $order,
            'message' => 'Заказ успешно создан'
        ]);
    }

    public function update(CreateOrderFormRequest $request, $id)
    {

    }

    public function delete($id)
    {

    }

    public function getOne($id)
    {

    }

    public function getAll()
    {

    }
}
