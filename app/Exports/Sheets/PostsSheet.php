<?php
namespace App\Exports\Sheets;


use App\Models\Employee;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class PostsSheet implements FromQuery, WithTitle
{

    /**
     * @inheritDoc
     */
    public function query()
    {
        return Post::all()->toQuery();
    }

    public function title(): string
    {
        return 'Должности';
    }
}
