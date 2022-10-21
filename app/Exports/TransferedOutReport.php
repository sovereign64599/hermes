<?php

namespace App\Exports;

use App\Models\TransferOutRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransferedOutReport implements FromCollection, WithHeadings
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
        $data = DB::table('transfer_out_records')->select('item_name','item_category','item_sub_category', 'item_quantity','item_quantity_deduct', 'item_barcode','item_cost','item_sell','form_number','custom_date','notes','user_name')->whereBetween('custom_date', [$this->from, $this->to])->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Item Category",
            "Item Sub Category",
            "Item Quantity",
            "Item Deduct Quantity",
            "Item Barcode",
            "Item Cost",
            "Item Sell",
            "Item Form Number",
            "Item Form Date",
            "Notes",
            "Reported By"
        ];
    }
}
