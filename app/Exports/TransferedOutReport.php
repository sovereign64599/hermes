<?php

namespace App\Exports;

use App\Models\TransferOutRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransferedOutReport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $from;
    protected $to;
    protected $count;

    function __construct($from,$to) {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $data = DB::table('transfer_out_records')->select('item_name','item_category','item_sub_category','item_barcode', 'item_quantity','item_quantity_deduct','item_cost','form_number','custom_date','notes','user_name')->whereBetween('custom_date', [$this->from, $this->to])->get();
        
        foreach($data as $row)
        {
            $this->count++;
            $arr[] = (array) $row;
        }
        $rowCount = $this->count + 1;
        array_push($arr,['','', '' , '', '', 'Total Cost:', '=SUM(G2:G'.$rowCount.')','', '', '', '']);  //Fuck this sheeeet
        $items = [
            'values' => collect($arr)
        ];
        return collect($items['values']);
    }

    public function map($row): array
    {
        return $row;
    }

    public function headings(): array
    {
        return [
            "Item Name",
            "Category",
            "Sub Category",
            "Barcode",
            "Quantity (before)",
            "Quantity Deducted",
            "Cost",
            "Form Number",
            "Form Date",
            "Notes",
            "Reported By"
        ];
    }
}
