<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $from;
    protected $to;

    function __construct($from,$to) {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $data = DB::table('deliveries')->select('item_name','item_category','item_sub_category','item_barcode','item_quantity_deduct','total_amount','custom_date','user_name')->whereBetween('custom_date', [$this->from, $this->to])->where('delivery_status', 'Delivered')->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Category",
            "Sub Category",
            "Barcode",
            "Quantity",
            "Amount",
            "Date",
            "Proccessed By",
        ];
    }
}
