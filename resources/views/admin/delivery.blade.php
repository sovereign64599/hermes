@extends('layouts.app')

@section('title', 'Delivery')

@section('content')
    <div class="row pages">
        <div class="col-lg">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Delivery Items</h4>
                    <p class="mb-0"><small>If item marked <span class="text-success">(Delivered)</span>, the total amount will be added to your sales.</small></p>
                    <p class="mb-0"><small>If item marked <span class="text-info">(For Delivery)</span>, the item quantity deduction will be applied.</small></p>
                    <p class="mb-0"><small>If item marked <span class="text-danger">(Cancel)</span>, Item quantity deduction will not applied.</small></p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Form #</th>
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
                                            <td>{{$delivery->form_number}}</td>
                                            <td>{{$delivery->item_name}}</td>
                                            <td>{{$delivery->item_category}}</td>
                                            <td>{{$delivery->item_sub_category}}</td>
                                            <td>{{$delivery->item_quantity_deduct}}</td>
                                            <td>{{$delivery->item_barcode}}</td>
                                            <td>{{$delivery->item_price}}</td>
                                            <td>{{$delivery->total_amount}}</td>
                                            <td>{{$delivery->custom_date}}</td>
                                            <td>{{$delivery->created_at->diffForHumans()}}</td>
                                            <td>{{$delivery->user_name}}</td>
                                            <td class="{{$status}}">{{$delivery->delivery_status}}</td>
                                            <td>
                                                @if($delivery->delivery_status == 'Pending')
                                                    <a href="{{route('action.for.deliver', $delivery->id)}}" class="btn-info btn-sm">For Delivery</a>
                                                @elseif($delivery->delivery_status == 'For Delivery')
                                                    <a href="{{route('action.delivered', $delivery->id)}}" data="{{$delivery->id}}" onclick="deleteUser(this)" class="btn-success btn-sm">Delivered</a>
                                                    <a href="{{route('action.cancelled', $delivery->id)}}" data="{{$delivery->id}}" onclick="deleteUser(this)" class="btn btn-danger btn-sm">Cancel</a>
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
                </div>
            </div>
        </div>  
    </div>
@endsection

{{-- @if($users->count() > 0)
@section('script')
    <script>
        function deleteUser(user){
            let userID = user.getAttribute('data');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                color: '#ffffff',
                background: '#24283b',
                showCancelButton: true,
                confirmButtonColor: '#d95650',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace('/dleete-user/' + userID);
                }
            })
        }
    </script>
@endsection
@endif --}}