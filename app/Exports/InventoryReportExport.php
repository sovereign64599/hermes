<?php

namespace App\Exports;

use App\Models\Items;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class InventoryReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $from_date;
    protected $to_date;

    function __construct($from_date,$to_date) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

     public function collection()
    {
        $data = DB::table('items')->select('item_name','item_category','item_sub_category','item_quantity', 'item_barcode', 'item_cost', 'item_sell', 'total_cost')->whereBetween('created_at', [$this->from_date, $this->to_date])->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Category",
            "Sub Category",
            "Quantity",
            "Barcode",
            "Cost",
            "Sell",
            "Total Cost"
        ];
    }
}
