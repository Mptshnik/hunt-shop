<?php

namespace App\Http\Controllers;

use App\Http\Requests\Provider\CreateProviderFormRequest;
use App\Http\Requests\Provider\UpdateProviderFormRequest;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function store(CreateProviderFormRequest $request)
    {
        $data = $request->validated();

        $provider = Provider::create($data);

        $response = [
            'provider' => $provider,
            'message' => 'Поставщик успешно создан'
        ];

        return response($response, 201);
    }

    public function update(UpdateProviderFormRequest $request, $id)
    {
        $provider = Provider::find($id);

        if($provider == null)
        {
            return response(['message' => "Поставщик с id=$id не найден"], 404);
        }

        $data = $request->validated();

        $provider->update($data);
        $provider->save();

        $response = [
            'provider' => $provider,
            'message' => 'Поставщик успешно обновлен'
        ];

        return response($response);
    }

    public function delete($id)
    {
        $provider = Provider::find($id);

        if($provider == null)
        {
            return response(['message' => "Поставщик с id=$id не найден"], 404);
        }

        $provider->delete();

        return response(['message' => 'Поставщик успешно удален']);
    }

    public function getAll()
    {
        return response(Provider::all());
    }

    public function getOne($id)
    {
        $provider = Provider::find($id);

        if($provider == null)
        {
            return response(['message' => "Поставщик с id=$id не найден"], 404);
        }

        return response($provider);
    }
}
