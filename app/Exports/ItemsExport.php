<?php

namespace App\Exports;

use App\Models\Items;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ItemsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('items')->select('item_name','item_category','item_sub_category','item_quantity','item_barcode','item_description','item_cost','item_sell','item_notes','created_at','updated_at')->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Item Category",
            "Item Sub Category",
            "Item Quantity",
            "Item Barcode",
            "Item Description",
            "Item Cost",
            "Item Sell",
            "Item Notes",
            "Item Created",
            "Item Last Update"
        ];
    }
}
