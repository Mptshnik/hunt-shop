<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientApplicationForm\CreateClientApplicationFormRequest;
use App\Http\Requests\ClientApplicationForm\UpdateClientApplicationFormRequest;
use App\Models\ClientApplicationForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientApplicationFormController extends Controller
{
    public function store(CreateClientApplicationFormRequest $request)
    {
        $data = $request->validated();

        $clientApplicationForm = ClientApplicationForm::create($data);

        //TODO присвоить id авторизованного пользователя
        //$clientApplicationForm->id = Auth::user()->employee_id;

        $id = $clientApplicationForm->id;

        $response = [
            'clientApplicationForm' => $clientApplicationForm->where('id', $id)->with('employee')->first(),
            'message' => 'Заявка клиента успешно создана'
        ];

        return response($response, 201);
    }

    public function update(UpdateClientApplicationFormRequest $request, $id)
    {
        $clientApplicationForm = ClientApplicationForm::find($id);

        if($clientApplicationForm == null)
        {
            return response(['message' => "Заявка клиента с id=$id не найдена"], 404);
        }

        $data = $request->validated();

        $clientApplicationForm->update($data);
        $clientApplicationForm->save();

        $id = $clientApplicationForm->id;

        $response = [
            'clientApplicationForm' => $clientApplicationForm->where('id', $id)->with('employee')->first(),
            'message' => 'Заявка клиента успешно обновлена'
        ];

        return response($response, 201);
    }

    public function delete($id)
    {
        $clientApplicationForm = ClientApplicationForm::find($id);

        if($clientApplicationForm == null)
        {
            return response(['message' => "Заявка клиента с id=$id не найдена"], 404);
        }

        $clientApplicationForm->delete();

        return response(['message' => 'Заявка клиента успешно удалена'], 201);
    }

    public function getAll()
    {
        return response(ClientApplicationForm::with('employee')->get(), 201);
    }

    public function getOne($id)
    {
        $clientApplicationForm = ClientApplicationForm::find($id);

        if($clientApplicationForm == null)
        {
            return response(['message' => "Заявка клиента с id=$id не найдена"], 404);
        }

        $id = $clientApplicationForm->id;

        return response($clientApplicationForm->where('id', $id)->with('employee')->first(), 201);
    }
}
