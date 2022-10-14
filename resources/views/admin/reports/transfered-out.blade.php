@extends('layouts.app')

@section('title', 'Reports | Transfered Out')

@section('content')
    <div class="row pages">
        <div class="col-lg">
            <div class="card shadow p-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="text-tertiary">Transfered In</h4>
                    <a href="{{route('export.report.transfer.out')}}" class="btn btn-sm text-light d-flex align-items-center"><i class="fas fa-file-export mr-2"></i><span>Export</span></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Item Category</th>
                                    <th>Item Sub Category</th>
                                    <th>Item Quantity <small>(before deduct)</small></th>
                                    <th>Item Deduct Quantity</th>
                                    <th>Item Barcode</th>
                                    <th>Item Cost</th>
                                    <th>Item Sell</th>
                                    <th>Item Form Number</th>
                                    <th>Item Form Date</th>
                                    <th>Notes</th>
                                    <th>Transfer By</th>
                                    <th>Transfer Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($reports->count() > 0)
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{$report->item_name}}</td>
                                            <td>{{$report->item_category}}</td>
                                            <td>{{$report->item_sub_category}}</td>
                                            <td class="text-success">{{$report->item_quantity}}</td>
                                            <td class="text-danger">{{$report->item_quantity_deduct}}</td>
                                            <td>{{$report->item_barcode}}</td>
                                            <td>{{$report->item_cost}}</td>
                                            <td>{{$report->item_sell}}</td>
                                            <td class="text-warning">{{$report->form_number}}</td>
                                            <td class="text-warning">{{$report->custom_date}}</td>
                                            <td>{{$report->notes}}</td>
                                            <td class="text-warning">{{ucfirst($report->user_name)}}</td>
                                            <td>{{$report->created_at->diffForHumans()}}</td>
                                        </tr>
                                    @endforeach
                                @else 
                                <tr><td>No record created</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
@endsection