<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RevenueReportExport implements FromCollection, WithHeadings
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
        $data = DB::table('sales')->select('custom_date','sales_amount','transaction_number','proccessed_by')->whereBetween('custom_date', [$this->from, $this->to])->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Date",
            "Revenue",
            "Transaction #",
            "Proccessed By",
        ];
    }
}
