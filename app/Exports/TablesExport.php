<?php

namespace App\Exports;

use App\Exports\Sheets\ClientsSheet;
use App\Exports\Sheets\EmployeesSheet;
use App\Exports\Sheets\InvoicesSheet;
use App\Exports\Sheets\ItemCategoriesSheet;
use App\Exports\Sheets\ItemInvoicesSheet;
use App\Exports\Sheets\ItemsSheet;
use App\Exports\Sheets\OrdersSheet;
use App\Exports\Sheets\PostsSheet;
use App\Exports\Sheets\PromotionsSheet;
use App\Exports\Sheets\ProvidersSheet;
use App\Exports\Sheets\PurchaseApplicationsSheets;
use App\Exports\Sheets\UserSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TablesExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [
            new EmployeesSheet(),
            new ClientsSheet(),
            new InvoicesSheet(),
            new ItemCategoriesSheet(),
            new ItemInvoicesSheet(),
            new ItemsSheet(),
            new OrdersSheet(),
            new PostsSheet(),
            new PromotionsSheet(),
            new ProvidersSheet(),
            new PurchaseApplicationsSheets(),
            new UserSheet()
        ];

        return $sheets;
    }
}
