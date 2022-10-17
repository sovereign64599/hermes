<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Items;
use App\Models\Sales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $totalItems = Items::count();
        $users = User::count();
        $delivered = Delivery::where('delivery_status', 'Delivered')->count();
        $sales = Sales::get();
        if($sales->count() > 0){
            $totalSales = 0;
            foreach($sales as $sale){
                $totalSales = (float)$totalSales + (float)$sale->sales_amount;
            }
        }else{
            $totalSales = 0;
        }

        // chart
        $earnings =  DB::select('select year(created_at) as year, month(created_at) as month, sum(sales_amount) as total_amount from sales group by year(created_at), month(created_at)');
        $salesArr = [];
        $salesCount = 0;
        for ($month = 1; $month <= 12; $month++) {
            if((int)$month == (int)$earnings[0]->month){
                $salesArr[$month] = (int)$earnings[0]->total_amount;
            }else{
                $salesArr[$month] = 0;
            }
        }
        
        return view('admin.dashboard.dashboard', compact(['totalItems', 'users', 'totalSales', 'delivered', 'salesArr']));
    }

    public function sales()
    {
        return view('admin.sales');
    }

    public function showUsers()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('admin.user', compact(['users']));
    }

    public function addUsers()
    {
        return view('admin.add-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users',
            'gender' => 'required|in:Male,Female',
            'role' => 'required|in:user,admin',
            'password' => 'required|confirmed'
        ]);

        $store = User::create([
            'name' => ucfirst($request->firstname) . ' ' . ucfirst($request->lastname),
            'firstname' => ucfirst($request->firstname),
            'middlename' => ucfirst($request->middlename),
            'lastname' => ucfirst($request->lastname),
            'email' => $request->email,
            'gender' => $request->gender,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        if($store){
            return redirect('/show-users')->with('success', ucfirst($request->firstname) . ' added as ' . ucfirst($request->role));
        }
    }

    public function editUsers(String $id)
    {
        $check = User::findOrFail($id);
        if($check){
            $user = $check;
        }else{
            return back()->with('error', 'No user found');
        }
        return view('admin.edit-user', compact(['user']));
    }

    public function updateUser($id, Request $request)
    {
        $check = User::findOrFail($id);
        if($check){
            $password = empty($request->password) ? $check->password : Hash::make($request->password);

            $update = $check->update([
                'name' => ucfirst($request->firstname) . ' ' . ucfirst($request->lastname),
                'firstname' => ucfirst($request->firstname),
                'middlename' => ucfirst($request->middlename),
                'lastname' => ucfirst($request->lastname),
                'email' => $request->email,
                'gender' => $request->gender,
                'role' => $request->role,
                'password' => $password
            ]);
            if($update){
                return redirect('/show-users')->with('success', ucfirst($request->firstname) . ' Updated successfully.');
            }
        }
    }

    public function deleteUser(String $id)
    {
        $check = User::findOrFail($id);
        
        if($check){
            if($check->role == 'user'){
                $check->delete();
                return redirect('/show-users')->with('success', ucfirst($check->firstname) . ' Deleted successfully.');
            }
            return back()->with('error', 'Something went wrong.');
        }
    }
}
