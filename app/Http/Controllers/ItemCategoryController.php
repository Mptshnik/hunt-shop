<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemCategory\CreateItemCategoryFormRequset;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function store(CreateItemCategoryFormRequset $requset)
    {
        $itemCategory = ItemCategory::create($requset->validated());

        return response([
            'item_category' => $itemCategory,
            'message' => 'Категория товара успешно добавлена'
        ]);
    }

    public function update(CreateItemCategoryFormRequset $requset, $id)
    {
        $itemCategory = ItemCategory::find($id);
        if($itemCategory == null)
        {
            return response(['message' => "Категория товара с id=$id не найдена"], 404);
        }

        $itemCategory->update($requset->validated());
        $itemCategory->save();

        return response([
            'item_category' => $itemCategory,
            'message' => 'Категория товара успешно изменена'
        ]);
    }

    public function delete($id)
    {
        $itemCategory = ItemCategory::find($id);
        if($itemCategory == null)
        {
            return response(['message' => "Категория товара с id=$id не найдена"], 404);
        }

        $itemCategory->delete();
        return response(['message' => 'Категория товара успешно удалена']);
    }

    public function getOne($id)
    {
        $itemCategory = ItemCategory::find($id);
        if($itemCategory == null)
        {
            return response(['message' => "Категория товара с id=$id не найдена"], 404);
        }

        return response($itemCategory);
    }

    public function getAll()
    {
        return response(ItemCategory::all());
    }
}
