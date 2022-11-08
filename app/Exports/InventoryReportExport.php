<?php

namespace App\Exports;

use App\Models\Items;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $from_date;
    protected $to_date;
    protected $count;

    function __construct($from_date,$to_date) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

     public function collection()
    {
        $data = DB::table('items')->select('item_name','item_category','item_sub_category','item_quantity', 'item_barcode', 'item_cost', 'item_sell', 'item_notes', 'item_photo', 'total_cost')->whereBetween('created_at', [$this->from_date, $this->to_date])->get();
        
        foreach($data as $row)
        {
            $this->count++;
            $arr[] = (array) $row;
        }
        $rowCount = $this->count + 1;
        array_push($arr,['','', '' , '', '', 'Total Cost:', '=SUM(G2:G'.$rowCount.')']);  //Fuck this sheeeet
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
            "name",
            "category",
            "subcategory",
            "quantity",
            "barcode",
            "description",
            "cost",
            "sell",
            "notes",
            "photo",
            "Total Cost"
        ];
    }
}
