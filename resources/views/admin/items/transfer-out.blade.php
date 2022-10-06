@extends('layouts.app')

@section('title', 'Transfer Out')

@section('content')
    <div class="row transfer-out">
        <div class="col-lg-8 t-o-left">
            <form action="" class="sticky-top">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-white">Choose Items</h5>
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="form-group">
                            <select class="form-select">
                                <option selected>Category</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-select">
                                <option selected>Sub Category</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search for..">
                        </div>
                    </div>
                </div>
            </form>
            <hr class="bg-dark">
            <div class="row t-o-items justify-content-center" id="showItems">
                {{-- show items here --}}
            </div>
        </div>
        <div class="col-lg-4 t-o-right">
            <div class="card sticky-top p-4">
                <form action="">
                    <div class="card-header py-4 ">
                        <h3>Cart</h3>
                        <small>Form # and date is auto generated. (Editable)</small>
                        <div class="d-flex justify-content-between">
                            <div class="m-0 text-tertiary">
                                Form # 
                                <input type="text" name="form_number" style='width:auto' class="ch-input" value="{{str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)}}">
                            </div>
                            <div class="date-now">
                                @php
                                    date_default_timezone_set('Asia/Manila');
                                    $dateToday = date("Y-m-d");
                                @endphp
                                <input type="date" name="date" class="ch-input" value="{{$dateToday}}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body h-100 pt-0 pb-4 position-relative" id="showCart">
                        {{-- show carts --}}
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <h5 class="text-light">Total Amount: $<span id="totalAmount">0</span></h5>
                        <input type="hidden" name="total_amount" id="totalAmountValue"">
                        <hr class="bg-light">
                        <button class="btn text-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }

        window.onload = function(){
            getItems();
            getCart();
        }

        // add comma to number >= 4 digits
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        async function getItems(){
            document.querySelector('#showItems').innerHTML = `<div class="w-100 text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            
            await axios.get('/show-items')
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#showItems').innerHTML = response.data.data;
                    }
                })
                .catch(function (error) {
                    document.querySelector('#showItems').innerHTML = error.response.data.errors;
                })
        }

        async function addCart(item){
            const dataID = item.getAttribute('data');
            
            await axios.post('/add-item-to-cart', {data:dataID}, {
                headers: headers
            })
                .then((response) => {
                    if(response.status == 200){
                        getItemQuantity(dataID)
                        Swal.fire({
                            icon: 'success',
                            text: response.data.success,
                            timer: 2000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                        });
                        getCart()
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        icon: 'warning',
                        text: error.response.data.errors,
                        timer: 2000,
                        color: '#ffffff',
                        background: '#24283b',
                        timerProgressBar: true,
                    });
                });
        }

        async function getCart(){
            await axios.get('/get-cart')
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#showCart').innerHTML = response.data.data;
                        document.querySelector('#totalAmount').innerHTML = numberWithCommas(response.data.totalAmount);
                        document.querySelector('#totalAmountValue').value = response.data.totalAmount;
                    }
                })
                .catch(function (error) {
                    document.querySelector('#showCart').innerHTML = error.response.data.errors;
                })
        }

        async function getItemQuantity(id){
            await axios.get('/get-item-quantity/'+id)
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('.item-qty-'+id).innerHTML = response.data.quantity;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
        }

    </script>
@endsection