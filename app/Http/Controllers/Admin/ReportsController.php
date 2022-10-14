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
        return view('admin.reports.transfered-in', compact(['reports']));
    }

    public function ExportTransferedInReport()
    {
        $report = TransferInRecord::all();
        if($report->count() > 0){
            return Excel::download(new TransferedInReport, 'Transfered-In-Report.xlsx');
        }
        return back()->with('error', 'No report created');
    }

    public function transferedOut()
    {
        $reports = TransferOutRecord::orderBy('created_at', 'DESC')->get();
        return view('admin.reports.transfered-out', compact(['reports']));
    }

    public function ExportTransferedOutReport()
    {
        $report = TransferOutRecord::all();
        if($report->count() > 0){
            return Excel::download(new TransferedOutReport, 'Transfered-Out-Report.xlsx');
        }
        return back()->with('error', 'No report created');
    }
    
}
