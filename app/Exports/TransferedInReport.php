<?php

namespace App\Exports;

use App\Models\TransferInRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransferedInReport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('transfer_in_records')->select('item_name','item_category','item_sub_category','item_quantity','item_quantity_added', 'item_barcode','item_cost','item_sell','form_number','custom_date','notes', 'user_name', 'created_at')->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Item Category",
            "Item Sub Category",
            "Item Quantity",
            "Item Added Quantity",
            "Item Barcode",
            "Item Cost",
            "Item Sell",
            "Item Form Number",
            "Item Form Date",
            "Notes",
            "Reported By",
            "Transfer Created",
        ];
    }
}
