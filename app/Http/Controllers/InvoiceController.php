<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function getOne($id)
    {
        $invoice = Invoice::with('seller')->with('order.client')->find($id);

        if($invoice == null)
        {
            return response(['message' => "Счет-фактура с id=$id не найдена"]);
        }

        return response($invoice);
    }

    public function getAll()
    {
        return response(Invoice::with('seller')->with('order')->with('order.client')->get());
    }
}
