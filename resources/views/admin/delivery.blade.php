@extends('layouts.app')

@section('title', 'Delivery')

@section('content')
    <div class="row pages">
        <div class="col-lg-2">
           <div class="card">
            <div class="card-body form-numbers">
                <h5>Form Number</h5>
                <ul>
                    @if($groupFormNumber->count() > 0)
                        @foreach($groupFormNumber as $row)
                            <a href="?form_number={{ $row->form_number }}"><li @if(isset($_GET['form_number']) && $_GET['form_number'] == $row->form_number) class="active"@endif># {{ $row->form_number }}</li></a>
                        @endforeach
                    @else 
                        <a href=""><li>No Form Number</li></a>
                    @endif
                </ul>
            </div>
           </div>
        </div>
        <div class="col-lg-10">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Delivery Items</h4>
                    @if(isset($_GET['form_number']))
                        <p class="mb-0"><small>If item marked <span class="text-success">(Delivered)</span>, the total amount will be added to your sales.</small></p>
                        <p class="mb-0"><small>If item marked <span class="text-info">(For Delivery)</span>, the item quantity deduction will be applied.</small></p>
                        <p class="mb-0"><small>If item marked <span class="text-danger">(Cancel)</span>, Item quantity deduction will not applied.</small></p>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($_GET['form_number']))
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Item Category</th>
                                    <th>Item Sub Category</th>
                                    <th>Item Quantity</th>
                                    <th>Barcode</th>
                                    <th>Price</th>
                                    <th>Total Amount</th>
                                    <th>Form Date</th>
                                    <th>Date Created</th>
                                    <th>Processed By</th>
                                    <th>Delivery Status</th>
                                    <th>Mark as</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($deliveries->count() > 0)
                                    @foreach ($deliveries as $delivery)
                                        @php 
                                            if ($delivery->delivery_status == 'Pending') {
                                                $status = 'text-warning';
                                            }else if($delivery->delivery_status == 'For Delivery'){
                                                $status = 'text-info';
                                            }else if($delivery->delivery_status == 'Cancelled'){
                                                $status = 'text-danger';
                                            }else{
                                                $status = 'text-success';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$delivery->item_name}}</td>
                                            <td>{{$delivery->item_category}}</td>
                                            <td>{{$delivery->item_sub_category}}</td>
                                            <td>{{$delivery->item_quantity_deduct}}</td>
                                            <td>{{$delivery->item_barcode}}</td>
                                            <td>{{$delivery->item_price}}</td>
                                            <td>{{$delivery->totalAmount_discounted != 0 ? 'discounted '. $delivery->totalAmount_discounted : $delivery->total_amount }}</td>
                                            <td>{{$delivery->custom_date}}</td>
                                            <td>{{$delivery->created_at->diffForHumans()}}</td>
                                            <td>{{$delivery->user_name}}</td>
                                            <td class="{{$status}}">{{$delivery->delivery_status}}</td>
                                            <td class="d-flex flex-column gap-1">
                                                @if($delivery->delivery_status == 'Pending')
                                                    <a href="{{route('action.for.deliver', $delivery->id)}}" class="btn-info btn-sm"><small>For Delivery</small></a>
                                                @elseif($delivery->delivery_status == 'For Delivery')
                                                    <a href="{{route('action.delivered', $delivery->id)}}" data="{{$delivery->id}}" onclick="deleteUser(this)" class="btn-success btn-sm"><small>Delivered</small></a>
                                                    <a href="{{route('action.cancelled', $delivery->id)}}" data="{{$delivery->id}}" onclick="deleteUser(this)" class="btn btn-danger btn-sm"><small>Cancel</small></a>
                                                @else
                                                    {{$delivery->delivery_status}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else 
                                <tr><td>No Items</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @else 
                    Select Form Number
                    @endif
                </div>
            </div>
        </div>  
    </div>
@endsection
