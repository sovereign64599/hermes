<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DeliveryReportExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $data = DB::table('deliveries')->select('item_name', 'form_number', 'item_category','item_sub_category','item_quantity_deduct', 'item_barcode','item_price','total_amount','custom_date','user_name')->whereBetween('custom_date', [$this->from, $this->to])->where('delivery_status', 'For Delivery')->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Transaction #",
            "Category",
            "Sub Category",
            "Quantity",
            "Barcode",
            "Price",
            "Amount",
            "Date",
            "Proccessed By",
        ];
    }
}
