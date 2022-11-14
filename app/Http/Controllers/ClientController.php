<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\CreateClientFormRequest;
use App\Http\Requests\Client\UpdateClientFormRequest;
use App\Models\Client;
use App\Models\JuridicalStatus;
use App\Models\Organisation;
use App\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function store(CreateClientFormRequest $request)
    {
        $data = $request->validated();
        $juridicalStatus = $data['juridical_status_id'];

        $client = new Client();

        if($juridicalStatus == JuridicalStatus::IS_ORGANISATION)
        {
            $organization = Organisation::create([
                'name' => $data['organisation_name'],
                'phone_number' => $data['phone_number']
            ]);

            $client->juridical_status_id = JuridicalStatus::IS_ORGANISATION;
            $client->organisation_id = $organization->id;
            $client->juridical_address = $data['juridical_address'];
            $client->physical_address = $data['physical_address'];
            $client->taxpayer_number = $data['taxpayer_number'];
        }
        else
        {
            $person = Person::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'],
                'phone_number' => $data['phone_number']
            ]);

            $client->juridical_status_id = JuridicalStatus::IS_PERSON;
            $client->person_id = $person->id;
            $client->physical_address = $data['physical_address'];
            $client->taxpayer_number = $data['taxpayer_number'];
        }

        $client->save();

        $response = [];

        $id = $client->id;

        if($juridicalStatus == JuridicalStatus::IS_PERSON)
        {
            $response = [
                'client' => $client->where('id', $id)->with('person')->with('juridicalStatus')->first(),
                'message' => 'Клиент успешно создан'
            ];
        }
        else
        {
            $response = [
                'client' => $client->where('id', $id)->with('organisation')->with('juridicalStatus')->first(),
                'message' => 'Клиент успешно создан'
            ];
        }

        return response($response, 201);
    }

    public function update(UpdateClientFormRequest $request, $id)
    {
        $client = Client::find($id);
        if($client == null)
        {
            return response(['message' => "Клиент с id=$id не найден"], 404);
        }

        $data = $request->validated();
        $juridicalStatus = $data['juridical_status_id'];

        $organisation_id = $client->organisation_id;
        $person_id = $client->person_id;

        if($juridicalStatus == JuridicalStatus::IS_ORGANISATION)
        {
            $organisation = Organisation::find($organisation_id);
            $organisation->update([
                'name' => $data['organisation_name'],
                'phone_number' => $data['phone_number']
            ]);
            $organisation->save();

            $client->juridical_status_id = JuridicalStatus::IS_ORGANISATION;
            $client->organisation_id = $organisation->id;
            $client->juridical_address = $data['juridical_address'];
            $client->physical_address = $data['physical_address'];
            $client->taxpayer_number = $data['taxpayer_number'];
        }
        else
        {
            $person = Person::find($person_id);
            $person->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'],
                'phone_number' => $data['phone_number']
            ]);

            $person->save();

            $client->juridical_status_id = JuridicalStatus::IS_PERSON;
            $client->person_id = $person->id;
            $client->physical_address = $data['physical_address'];
            $client->taxpayer_number = $data['taxpayer_number'];
        }

        $client->update();
        $client->save();

        $response = [];

        $id = $client->id;

        if($juridicalStatus == JuridicalStatus::IS_PERSON)
        {
            $response = [
                'client' => $client->where('id', $id)->with('person')->with('juridicalStatus')->first(),
                'message' => 'Клиент успешно изменен'
            ];
        }
        else
        {
            $response = [
                'client' => $client->where('id', $id)->with('organisation')->with('juridicalStatus')->first(),
                'message' => 'Клиент успешно изменен'
            ];
        }

        return response($response, 201);
    }

    public function delete($id)
    {
        $client = Client::find($id);

        if($client == null)
        {
            return response(['message' => "Клиент с id=$id не найден"], 404);
        }

        $client->delete();

        if($client->juridical_status_id == JuridicalStatus::IS_ORGANISATION)
        {
            $organisation = Organisation::find($client->organisation_id);
            $organisation->delete();
        }
        else
        {
            $person = Organisation::find($client->person_id);
            $person->delete();
        }

        return response(['message' => 'Клиент успешно удален'], 201);
    }

    public  function getAll()
    {
        return Client::with('juridicalStatus')
            ->with('person')->with('organisation')->get();
    }

    public function getOne($id)
    {
        $client = Client::where('id', $id)->with('juridicalStatus')
            ->with('person')->with('organisation')->first();

        if($client == null)
        {
            return response(['message' => "Клиент с id=$id не найден"], 404);
        }

        return response($client, 201);
    }
}
