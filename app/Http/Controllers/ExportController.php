<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Exports\TablesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ExportController extends Controller
{
    public function exportAllData()
    {
        return (new TablesExport())->download('hunt_shop.xlsx');
    }
}
