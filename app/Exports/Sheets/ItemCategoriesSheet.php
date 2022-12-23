<?php
namespace App\Exports\Sheets;


use App\Models\Employee;
use App\Models\ItemCategory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class ItemCategoriesSheet implements FromQuery, WithTitle
{

    /**
     * @inheritDoc
     */
    public function query()
    {
        return ItemCategory::all()->toQuery();
    }

    public function title(): string
    {
        return 'Категории товаров';
    }
}
