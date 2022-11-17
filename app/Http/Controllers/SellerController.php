<?php

namespace App\Http\Controllers;

use App\Http\Requests\Seller\CreateUpdateSellerFormRequest;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function store(CreateUpdateSellerFormRequest $request)
    {
        $rowsCount = Seller::count();

        if($rowsCount > 0)
        {
            return response(['message' => 'Данные организации уже заполнены. Удалите или измените устаревшие данные'], 403);
        }

        $data = $request->validated();

        $seller = Seller::create($data);

        $response = [
            'seller' => $seller,
            'message' => 'Информация об организации успешно сохранена'
        ];

        return response($response, 201);
    }

    public function update(CreateUpdateSellerFormRequest $request, $id)
    {
        $seller = Seller::find($id);
        if($seller == null)
        {
            return response(['message' => "Организация с id=$id не найдена"], 404);
        }

        $data = $request->validated();

        $seller->update($data);
        $seller->save();

        $response = [
            'seller' => $seller,
            'message' => 'Информация об организации успешно изменена'
        ];

        return response($response);
    }

    public function delete($id)
    {
        $seller = Seller::find($id);
        if($seller == null)
        {
            return response(['message' => "Организация с id=$id не найдена"], 404);
        }

        $seller->delete();

        return response(['message' => 'Информация об организации успешно удалена']);
    }

    public function getOne($id)
    {
        $seller = Seller::find($id);
        if($seller == null)
        {
            return response(['message' => "Организация с id=$id не найдена"], 404);
        }

        return response($seller);
    }
}
