<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransferedInReport;
use App\Exports\TransferedOutReport;
use App\Http\Controllers\Controller;
use App\Models\TransferInRecord;
use App\Models\TransferOutRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function transferedIn()
    {
        $reports = TransferInRecord::orderBy('created_at', 'DESC')->get();
        if(isset($_GET['from']) && isset($_GET['to'])){
            $reports = TransferInRecord::whereBetween('custom_date', [$_GET['from'], $_GET['to']])->get();
        }
        return view('admin.reports.transfered-in', compact(['reports']));
    }

    public function ExportTransferedInReport($from, $to)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to)) {
            $report = TransferInRecord::all();
            if($report->count() > 0){
                return Excel::download(new TransferedInReport($from, $to), date('F j, Y', strtotime($from)).' to '.date('F j, Y', strtotime($to)).'-Transfered-in.xlsx');
            }
            return back()->with('error', 'No report created');
        }   
        return back()->with('error', 'Date is invalid');
    }

    public function transferedOut()
    {
        $reports = TransferOutRecord::orderBy('created_at', 'DESC')->get();
        if(isset($_GET['from']) && isset($_GET['to'])){
            $reports = TransferOutRecord::whereBetween('custom_date', [$_GET['from'], $_GET['to']])->get();
        }
        return view('admin.reports.transfered-out', compact(['reports']));
    }

    public function ExportTransferedOutReport($from, $to)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to)) {
            $report = TransferOutRecord::all();
            if($report->count() > 0){
                return Excel::download(new TransferedOutReport($from, $to), date('F j, Y', strtotime($from)).' to '.date('F j, Y', strtotime($to)).'-Transfered-out.xlsx');
            }
            return back()->with('error', 'No report created');
        }   
        return back()->with('error', 'Date is invalid');
    }
    
}
