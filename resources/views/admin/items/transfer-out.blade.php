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
                        <h3>Order</h3>
                        <div class="d-flex justify-content-between">
                            <div class="m-0 text-tertiary">
                                Form # 
                                <input type="text" name="form_number" style='width:auto' class="ch-input" value="{{str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)}}">
                            </div>
                            <div class="date-now">
                                @php
                                    date_default_timezone_set('Asia/Manila');
                                    $date = "1996-06-25";
                                    $newDate = date("Y-m-d");
                                @endphp
                                <input type="date" name="date" class="ch-input" value="{{$newDate}}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body h-100 pt-0 pb-4 position-relative">
                        <div class="card mb-2 order-item">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <img class="img-fluid" src="https://www.thespruce.com/thmb/lbeqtgmmoRpaeMtbCH568V-8CBI=/3888x2592/filters:no_upscale():max_bytes(150000):strip_icc()/c-pvc-and-u-pvc-fittings-172717498-5ac54ef1a474be00365ae8e6.jpg" alt="test">
                                    </div>
                                    <div class="col-lg-6 d-flex flex-column">
                                        <small>Test Item 1</small>
                                        <small>Lorem ipsum dolor sit amet</small>
                                    </div>
                                    <div class="col-lg-2 d-flex align-items-center justify-content-end">
                                        <input type="number" value="1" class="form-control ch-input">
                                    </div>
                                    <div class="col-lg-2 d-flex align-items-center justify-content-end">
                                        <button class="btn">
                                            <i class="fas fa-trash text-light"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2 order-item">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <img class="img-fluid" src="https://www.thespruce.com/thmb/lbeqtgmmoRpaeMtbCH568V-8CBI=/3888x2592/filters:no_upscale():max_bytes(150000):strip_icc()/c-pvc-and-u-pvc-fittings-172717498-5ac54ef1a474be00365ae8e6.jpg" alt="test">
                                    </div>
                                    <div class="col-lg-6 d-flex flex-column">
                                        <small>Test Item 2</small>
                                        <small>Lorem ipsum dolor sit amet</small>
                                    </div>
                                    <div class="col-lg-2 d-flex align-items-center justify-content-end">
                                        <input type="number" value="2" class="form-control ch-input">
                                    </div>
                                    <div class="col-lg-2 d-flex align-items-center justify-content-end">
                                        <button class="btn">
                                            <i class="fas fa-trash text-light"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button class="btn text-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        getItems();

        function getItems(){
            document.querySelector('#showItems').innerHTML = `<div class="w-100 text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            
            axios.get('/show-items')
                .then(function (response) {
                    console.log(response);
                    document.querySelector('#showItems').innerHTML = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
        }

    </script>
@endsection