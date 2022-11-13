<?php

namespace App\Http\Controllers;
use App\Http\Requests\Employee\CreateEmployeeFormRequest;
use App\Http\Requests\Employee\UpdateEmployeeFormRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use PharIo\Manifest\Email;

class EmployeeController extends Controller
{
    public function store(CreateEmployeeFormRequest $request)
    {
        $data = $request->validated();

        $employee = Employee::create($data);

        $response = [
            'employee' => $employee,
            'message' => 'Сотрудник успешно создан'
        ];

        return response($response, 201);
    }

    public function update(UpdateEmployeeFormRequest $request, $id)
    {
        $employee = Employee::find($id);

        if($employee == null)
        {
            return response(['message' => "Сотрудник с id=$id не найден"], 404);
        }

        $data = $request->validated();

        $employee->update($data);
        $employee->save();

        $response = [
            'employee' => $employee,
            'message' => 'Сотрудник успешно изменен'
        ];

        return response($response, 201);
    }

    public function delete($id)
    {
        $employee = Employee::where('id', $id);

        if($employee == null)
        {
            return response(['message' => "Сотрудник с id=$id не найден"], 404);
        }

        $employee->delete();

        return response(['message' => 'Сотрудник успешно удален'], 201);
    }

    public function getAll()
    {
        return Employee::all();
    }

    public function getOne($id)
    {
        $employee = Employee::find($id);

        if($employee == null)
        {
            return response(['message' => "Сотрудник с id=$id не найден"], 404);
        }

        return response($employee, 201);
    }
}
