<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemInvoice\CreateItemInvoiceFormRequest;
use App\Http\Requests\ItemInvoice\UpdateItemInvoiceFormRequest;
use App\Models\Employee;
use App\Models\ItemInvoice;
use Illuminate\Http\Request;

class ItemInvoiceController extends Controller
{
    public function store(CreateItemInvoiceFormRequest $request)
    {
        $data = $request->validated();

        $itemInvoice = ItemInvoice::create($data);

        $response = [
            'item_invoice' => $itemInvoice->where('id', $itemInvoice->id)
                ->with('seller')->with('provider')->first(),
            'message' => 'Товарная накладная успешно создана'
        ];

        return response($response);
    }

    public function update(UpdateItemInvoiceFormRequest $request, $id)
    {
        $itemInvoice = ItemInvoice::find($id);

        if($itemInvoice == null)
        {
            return response(['message' => "Товарная накладная с id=$id не найдена"], 404);
        }

        $data = $request->validated();

        $itemInvoice->update($data);
        $itemInvoice->save();

        $response = [
            'item_invoice' => $itemInvoice->where('id', $id)
                ->with('seller')->with('provider')->first(),
            'message' => 'Товарная накладная успешно изменена'
        ];

        return response($response);
    }

    public function delete($id)
    {
        $itemInvoice = ItemInvoice::find($id);

        if($itemInvoice == null)
        {
            return response(['message' => "Товарная накладная с id=$id не найдена"], 404);
        }

        $itemInvoice->delete();

        return response(['message' => 'Товарная накладная успешно удалена']);
    }

    public function getOne($id)
    {
        $itemInvoice = ItemInvoice::where('id', $id)->with('provider')->with('seller')->first();

        if($itemInvoice == null)
        {
            return response(['message' => "Товарная накладная с id=$id не найдена"], 404);
        }

        return response($itemInvoice);
    }

    public function getAll()
    {
        return response(ItemInvoice::with('provider')->with('seller')->get());
    }
}
