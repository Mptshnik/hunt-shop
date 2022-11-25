<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item\CreateItemFormRequset;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(CreateItemFormRequset $requset)
    {
        $item = Item::create($requset->validated());

        return response([
            'item' => $item->where('id', $item->id)
                ->with('itemInvoice')
                ->with('promotion')
                ->with('itemCategory')->get(),
            'message' => 'Товар успешно добавлен'
        ]);
    }

    public function update(CreateItemFormRequset $requset, $id)
    {
        $item = Item::find($id);

        if($item == null)
        {
            return response(['message' => "Товар с id=$id не найден"]);
        }

        $item->update($requset->validated());
        $item->save();

        return response([
            'item' => $item->where('id', $item->id)
                ->with('itemInvoice')
                ->with('promotion')
                ->with('itemCategory')->get(),
            'message' => 'Товар успешно изменен'
        ]);
    }

    public function delete($id)
    {
        $item = Item::find($id);

        if($item == null)
        {
            return response(['message' => "Товар с id=$id не найден"]);
        }

        $item->delete();

        return response(['message' => 'Товар успешно удален']);
    }

    public function getOne($id)
    {
        $item = Item::find($id);

        if($item == null)
        {
            return response(['message' => "Товар с id=$id не найден"]);
        }

        return response($item->where('id', $id)
            ->with('itemInvoice')
            ->with('promotion')
            ->with('itemCategory')->get());
    }

    public function getAll()
    {
        return response(Item::with('itemInvoice')
            ->with('promotion')
            ->with('itemCategory')->get());
    }
}
