<?php

namespace App\Exports;

use App\Models\Delivery;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RevenueReportExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        // $data = DB::table('sales')->select('custom_date','sales_amount','transaction_number','proccessed_by')->whereBetween('custom_date', [$this->from, $this->to])->get();
        $data = Sales::select([
            'custom_date',
            \DB::raw("SUM(sales_amount) as total_sales"),
            \DB::raw('transaction_number as t_n'),
        ])->groupBy('custom_date')->groupBy('t_n')->whereBetween('custom_date', [$this->from, $this->to])->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            "Date",
            "Revenue",
            "Transaction #",
        ];
    }
}
