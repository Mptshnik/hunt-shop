<?php
namespace App\Exports\Sheets;


use App\Models\Client;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClientsSheet implements FromQuery, WithTitle
{

    /**
     * @inheritDoc
     */
    public function query()
    {
        return Client::with('person')->with('organisation');
    }

    public function title(): string
    {
        return 'Клиенты';
    }
}
