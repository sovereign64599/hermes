<?php

namespace App\Exports;

use App\Models\Items;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Items::all();
    }

    public function headings(): array
    {
        return [
            "item_id",
            "item_name",
            "item_category",
            "item_sub_category",
            "item_quantity",
            "item_barcode",
            "item_description",
            "item_cost",
            "item_sell",
            "item_notes",
            "item_photo",
            "created_at",
            "updated_at"
        ];
    }
}
