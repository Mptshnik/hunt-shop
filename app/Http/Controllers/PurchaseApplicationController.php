<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientApplicationForm\CreateClientApplicationFormRequest;
use App\Http\Requests\ClientApplicationForm\UpdateClientApplicationFormRequest;
use App\Http\Requests\PurchaseApplication\CreatePurchaseApplicationFormRequest;
use App\Http\Requests\PurchaseApplication\UpdatePurchaseApplicationFormRequest;
use App\Models\ClientApplicationForm;
use App\Models\Employee;
use App\Models\PurchaseApplication;
use Illuminate\Http\Request;

class PurchaseApplicationController extends Controller
{
    public function store(CreatePurchaseApplicationFormRequest $request)
    {
        $data = $request->validated();

        $employee_id = $data['employee_id'];
        $employee = Employee::find($employee_id);
        if($employee == null)
        {
            return response(['message' => "Сотрудник с id=$employee_id не найден"], 404);
        }

        $purchaseApplication = PurchaseApplication::create($data);

        //TODO присвоить id авторизованного пользователя
        //$purchaseApplication->id = Auth::user()->employee_id;

        $id = $purchaseApplication->id;

        $response = [
            'purchaseApplication' => $purchaseApplication->where('id', $id)->with('employee')->first(),
            'message' => 'Заявка на закупку товаров успешно создана'
        ];

        return response($response, 201);
    }

    public function update(UpdatePurchaseApplicationFormRequest $request, $id)
    {
        $purchaseApplication = ClientApplicationForm::find($id);

        if($purchaseApplication == null)
        {
            return response(['message' => "Заявка на закупку товаров с id=$id не найдена"], 404);
        }

        $data = $request->validated();

        $employee_id = $data['employee_id'];
        $employee = Employee::find($employee_id);
        if($employee == null)
        {
            return response(['message' => "Сотрудник с id=$employee_id не найден"], 404);
        }


        $purchaseApplication->update($data);
        $purchaseApplication->save();

        $id = $purchaseApplication->id;

        $response = [
            'purchaseApplication' => $purchaseApplication->where('id', $id)->with('employee')->first(),
            'message' => 'Заявка на закупку товаров успешно обновлена'
        ];

        return response($response);
    }

    public function delete($id)
    {
        $purchaseApplication = PurchaseApplication::find($id);

        if($purchaseApplication == null)
        {
            return response(['message' => "Заявка на закупку товаров с id=$id не найдена"], 404);
        }

        $purchaseApplication->delete();

        return response(['message' => 'Заявка на закупку товаров успешно удалена']);
    }

    public function getAll()
    {
        return response(PurchaseApplication::with('employee')->get());
    }

    public function getOne($id)
    {
        $purchaseApplication = PurchaseApplication::find($id);

        if($purchaseApplication == null)
        {
            return response(['message' => "Заявка на закупку товаров с id=$id не найдена"], 404);
        }

        $id = $purchaseApplication->id;

        return response($purchaseApplication->where('id', $id)->with('employee')->first());
    }
}
