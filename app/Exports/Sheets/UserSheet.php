<?php

namespace App\Exports\Sheets;


use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserSheet implements FromQuery, WithTitle
{

    /**
     * @inheritDoc
     */
    public function query()
    {
        return User::all()->toQuery();
    }

    public function title(): string
    {
        return 'Пользователи';
    }
}
