<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        $data = DB::table('deliveries')->select('item_name','item_category','item_sub_category','item_barcode','item_quantity_deduct','total_amount','custom_date','user_name')->whereBetween('custom_date', [$this->from, $this->to])->where('delivery_status', 'Delivered')->get();
        foreach($data as $row)
        {
            $this->count++;
            $arr[] = (array) $row;
        }
        $rowCount = $this->count + 1;
        array_push($arr,['','', '' , '', 'Total Value:', '=SUM(F2:F'.$rowCount.')','', '']);  //Added Total of Orders in Excel sheet
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
            "Quantity",
            "Amount",
            "Date",
            "Processed By",
        ];
    }
}
