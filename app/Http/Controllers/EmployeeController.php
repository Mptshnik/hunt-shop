<?php

namespace App\Http\Controllers;
use App\Http\Requests\Employee\CreateEmployeeFormRequest;
use App\Http\Requests\Employee\UpdateEmployeeFormRequest;
use App\Models\Employee;
use App\Exports\EmployeesExport;
use http\Env\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use PharIo\Manifest\Email;

class EmployeeController extends Controller
{
    public function export()
    {
        return (new EmployeesExport())->download('employees.xlsx');
    }

    public function filterByPostId($id)
    {
        return Employee::with('post')->where('post_id', $id)->get();
    }

    public function store(CreateEmployeeFormRequest $request)
    {
        $data = $request->validated();

        $employee = Employee::create($data);

        $id = $employee->id;

        $response = [
            'employee' => $employee->where('id', $id)->with('post')->first(),
            'message' => 'Сотрудник успешно создан'
        ];

        return response($response, 201);
    }

    private function getEmployee($id) : Employee
    {
        return Employee::where('id', $id)->with('post')->first();
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
            'employee' => $employee->where('id', $id)->with('post')->first(),
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

        return response(Employee::with('post')->get(), 201);
    }

    public function getOne($id)
    {
        $employee = Employee::where('id', $id)->with('post')->first();

        if($employee == null)
        {
            return response(['message' => "Сотрудник с id=$id не найден"], 404);
        }

        return response($employee, 201);
    }
}
