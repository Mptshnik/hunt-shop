<?php

namespace App\Http\Controllers;

use App\Http\Requests\Promotion\CreatePromotionFormRequest;
use App\Http\Requests\Promotion\UpdatePromotionFormRequest;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function store(CreatePromotionFormRequest $request)
    {
        $data = $request->validated();

        $promotion = Promotion::create($data);

        $response = [
          'promotion' => $promotion,
          'message' => 'Акция успешно создана'
        ];

        return response($response, 201);
    }

    public function update(UpdatePromotionFormRequest $request, $id)
    {
        $promotion = Promotion::find($id);

        if($promotion == null)
        {
            return response(['message' => "Акция с id=$id не найдена"], 404);
        }

        $data = $request->validated();

        $promotion->update($data);
        $promotion->save();

        $response = [
            'promotion' => $promotion,
            'message' => 'Акция успешно обновлена'
        ];

        return response($response, 201);
    }

    public function delete($id)
    {
        $promotion = Promotion::find($id);

        if($promotion == null)
        {
            return response(['message' => "Акция с id=$id не найдена"], 404);
        }

        $promotion->delete();

        return response(['message' => 'Акция успешно удалена']);
    }

    public function getAll()
    {
        return response(Promotion::all());
    }

    public function getOne($id)
    {
        $promotion = Promotion::find($id);

        if($promotion == null)
        {
            return response(['message' => "Акция с id=$id не найдена"], 404);
        }

        return response($promotion);
    }
}
