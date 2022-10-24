<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DeliveryReportExport;
use App\Exports\InventoryReportExport;
use App\Exports\RevenueReportExport;
use App\Exports\SalesReportExport;
use App\Exports\TransferedInReport;
use App\Exports\TransferedOutReport;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Items;
use App\Models\Sales;
use App\Models\TransferInRecord;
use App\Models\TransferOutRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // TRANSFERED IN REPORT====================================================================================================================================================
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

    // TRANSFERED OUT REPORT====================================================================================================================================================
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

    // DELIVERY REPORT====================================================================================================================================================
    public function deliveryReport()
    {
        $reports = Delivery::orderBy('created_at', 'DESC')->where('delivery_status', 'For Delivery')->get();
        if(isset($_GET['from']) && isset($_GET['to'])){
            $reports = Delivery::whereBetween('custom_date', [$_GET['from'], $_GET['to']])->where('delivery_status', 'For Delivery')->get();
        }
        return view('admin.reports.delivery-report', compact(['reports']));
    }

    public function ExportDeliveryReport($from, $to)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to)) {
            $report = Delivery::all();
            if($report->count() > 0){
                return Excel::download(new DeliveryReportExport($from, $to), date('F j, Y', strtotime($from)).' to '.date('F j, Y', strtotime($to)).'-Delivery-Report.xlsx');
            }
            return back()->with('error', 'No report created');
        }   
        return back()->with('error', 'Date is invalid');
    }
    
    // SALES REPORT====================================================================================================================================================
    public function salesReport()
    {
        $reports = Delivery::orderBy('created_at', 'DESC')->where('delivery_status', 'Delivered')->get();
        if(isset($_GET['from']) && isset($_GET['to'])){
            $reports = Delivery::whereBetween('custom_date', [$_GET['from'], $_GET['to']])->where('delivery_status', 'Delivered')->get();
        }
        return view('admin.reports.sales-report', compact(['reports']));
    }

    public function ExportSalesReport($from, $to)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to)) {
            $report = Delivery::all();
            if($report->count() > 0){
                return Excel::download(new SalesReportExport($from, $to), date('F j, Y', strtotime($from)).' to '.date('F j, Y', strtotime($to)).'-Sales-Report.xlsx');
            }
            return back()->with('error', 'No report created');
        }   
        return back()->with('error', 'Date is invalid');
    }

    // REVENUE REPORT====================================================================================================================================================
    public function revenueReport()
    {
        $reports = Sales::orderBy('created_at', 'DESC')->get();
        if(isset($_GET['from']) && isset($_GET['to'])){
            $reports = Sales::whereBetween('custom_date', [$_GET['from'], $_GET['to']])->get();
        }
        return view('admin.reports.revenue-report', compact(['reports']));
    }

    public function ExportRevenueReport($from, $to)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to)) {
            $report = Delivery::all();
            if($report->count() > 0){
                return Excel::download(new RevenueReportExport($from, $to), date('F j, Y', strtotime($from)).' to '.date('F j, Y', strtotime($to)).'-Revenue-Report.xlsx');
            }
            return back()->with('error', 'No report created');
        }   
        return back()->with('error', 'Date is invalid');
    }

    // INVENTORY REPORT====================================================================================================================================================
    public function inventoryReport()
    {
        date_default_timezone_set('Asia/Manila');
        $reports = Items::orderBy('created_at', 'DESC')->get();
        if(isset($_GET['from']) && isset($_GET['to'])){
            $from = Carbon::parse($_GET['from']);
            $reports = Items::whereBetween('created_at', [$from->format('Y-m-d h:i:s'), $_GET['to']])->get();
        }
        return view('admin.reports.inventory-report', compact(['reports']));
    }

    public function ExportInventoryReport($from, $to)
    {
        date_default_timezone_set('Asia/Manila');
        $from_date = Carbon::parse($from);
        $to_date = Carbon::parse($to);
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to)) {
            $report = Items::all();
            if($report->count() > 0){
                return Excel::download(new InventoryReportExport($from_date, $to_date), date('F j, Y', strtotime($from)).' to '.date('F j, Y', strtotime($to)).'-Inventory-Report.xlsx');
            }
            return back()->with('error', 'No report created');
        }   
        return back()->with('error', 'Date is invalid');
    }
}
