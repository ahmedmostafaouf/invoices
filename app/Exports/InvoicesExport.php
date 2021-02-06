<?php

namespace App\Exports;


use App\Models\Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{

    public function collection()
    {
        return Invoices::all();
    }
}
