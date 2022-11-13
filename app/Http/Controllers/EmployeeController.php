<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeCreateFormRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use PharIo\Manifest\Email;

class EmployeeController extends Controller
{
    public function store(EmployeeCreateFormRequest $request)
    {
        $data = $request->validated();

        $data['passport_series'] = Hash::make($request['passport_series']);
        $data['passport_number'] = Hash::make($request['passport_number']);

        $employee = Employee::create($data);

        return response()->json($employee);
    }

    public function update(EmployeeCreateFormRequest $request, $id)
    {
        $employee = Employee::find($id);
        $employee_db = Employee::where('passport_series', $request->passport_series)
            ->andWhere('passport_number', $request->passport_number);

        if($employee == null)
        {
            return response(['message' => "Employee with id=$id not found"], 404);
        }

        if($employee_db != null)
        {
            if($employee_db->id != $id)
            {
                return response(["passport" => 'Сотрудник с такими паспортными данными уже существует'], 400);
            }
        }

        $data = $request->validated();

        $data['passport_series'] = Hash::make($request['passport_series']);
        $data['passport_number'] = Hash::make($request['passport_number']);

        $employee->update($data);
        $employee->save();

        return response();
    }

    public function delete($id)
    {
       $employee = Employee::where('id', $id);
        if($employee == null)
        {
            return ['message' => "Не удалось найти сотрудника с id=$id"];
        }

        $employee->delete();

        return [
            'message' => 'Сотрудник успешно удален',
            'success' => true
        ];
    }

    public function getAll()
    {
        return Employee::all();
    }
}
