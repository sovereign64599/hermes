@extends('layouts.app')

@section('title', 'Sales')

@section('content')
    <div class="row pages">
        <div class="col-lg-4">
            <form id="add_list">
                <div class="card text-left sticky-top p-2">
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="form-group">
                                <label><small>Search Item Name/barcode/description</small></label>
                                <div class="auto-sugguestion">
                                    <input type="search" class="form-control" id="search_item" placeholder="Search Name / barcode / description"  oninput="collectItems(this)" autocomplete="false">
                                    <i class="fas fa-search"></i>
                                    <ul class="list-item-option">
                                        
                                    </ul>
                                </div>
                            </div>
                            @if($getSubCategories->count() > 0)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Category</small></label>
                                    <input readonly class="form-control item-category" placeholder="Category" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Sub Category</small></label>
                                    <input readonly class="form-control item-sub-category" placeholder="Sub Category" disabled>
                                </div>
                            </div>
                            @else 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label><small>Category and Sub Category List is not created yet.</small></label>
                                        <br />
                                        <a class="btn btn-tertiary text-light" href="{{route('categories')}}">Create Categories</a>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item barcode</small></label>
                                    <input readonly class="form-control item-barcode" placeholder="Item Bar code" maxlength="6" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Price</small></label>
                                    <input type="text" id="item_price" class="form-control item-sell bg-black" placeholder="Unit Price" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Current Quantity</small></label>
                                    <input readonly class="form-control item-quantity" placeholder="Current Quantity" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Deduct Quantity</small></label>
                                    <input type="text" id="item_deduct_Quantity" class="form-control bg-black" class="form-control" placeholder="Deduct quantity" oninput="this.value = this.value.replace(/[^-\d+$.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><small>Item Description</small></label>
                                <textarea readonly rows="3" class="form-control item-description" placeholder="Description" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn text-light">Add to list</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-8">
            <form id="submit_list">
                @csrf
                <div class="card shadow p-2 mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between text-tertiary">
                            <div class="m-0">
                                Form # 
                                <input type="text" name="form_number" style='width:auto' class="ch-input" value="{{str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)}}">
                            </div>
                            <div class="date-now">
                                @php
                                    $dateToday = date("Y-m-d");
                                @endphp
                                Date:
                                <input type="date" name="custom_date" class="ch-input" value="{{$dateToday}}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Item Barcode</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Amount to pay</th>
                                        <th>Action</th>
                                        <th>For Delivery</th>
                                    </tr>
                                </thead>
                                <tbody id="showList">
                                    {{-- show list here 06040241 06030340 04030811 --}}
                                </tbody>
                            </table>
                            <p class="text-right"><small class="text-tertiary limit">Items (0/10)</small></p>
                            @if(Auth::user()->role == 'Admin')
                            <p class="text-right"><small class="text-light">Add Discount</small></p>
                            <div class="input-group w-25 float-right">
                                <input type="text" class="form-control item-discount" placeholder="Enter Discount %" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                <button class="btn btn-outline-secondary text-light" type="button" role="button" onclick="updateSalesDiscount(document.querySelector('.item-discount').value)">Apply</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">

                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-left gap-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-light mb-0">Total Price:</h6>
                                        <span class="text-light totalAmountToPay">0</span>
                                    </div>
                                    @if(Auth::user()->role == 'Admin')
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-light mb-0">Discount:</h6>
                                        <span class="text-light totalAmountToPay item-discount-text">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-light mb-0">Total:</h6>
                                        <span class="text-light item-totalAmountDiscounted">0</span>
                                    </div>
                                    @endif
                                    <button class="btn text-light">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        const addList = document.querySelector('#add_list');
        const submit_list = document.querySelector('#submit_list');
        const searchItemInput = document.querySelector('#search_item');
        const updateItem = document.querySelector('#updateItems');
        const listItemOption = document.querySelector('.list-item-option');
        const showList = document.querySelector('#showList');

        window.onload = () => {
            getSalesList()
        }

        addList.addEventListener('submit', function(e){
            e.preventDefault();
            let id = document.querySelector('#search_item').getAttribute('data')
            let price = document.querySelector('.item-sell').value
            let deductQuantity = document.querySelector('#item_deduct_Quantity').value
            let item_price = document.querySelector('#item_price').value
            let formData = {
                id: id,
                deductQty: deductQuantity,
                price: item_price
            };
            axios.post('/add-sales-list', formData)
                .then((response) => {
                    if(response.status == 200){
                        addList.reset();
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            text: response.data.message,
                            animation: false,
                            position: 'top-right',
                            showConfirmButton: false,
                            timer: 3000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        getSalesList()
                    }
                })
                .catch(function (error) {
                    
                    if (error.response.status === 422) {
                        if(error.response.data.errors.deductQty){
                            Swal.fire({
                                icon: 'error',
                                text: error.response.data.errors.deductQty,
                                timer: 2000,
                                timerProgressBar: true,
                                color: '#ffffff',
                                background: '#24283b'
                            });
                        }else{
                            Swal.fire({
                            icon: 'error',
                            text: error.response.data.errors.id,
                            timer: 2000,
                            timerProgressBar: true,
                            color: '#ffffff',
                            background: '#24283b'
                        });
                        }
                    }else if(error.response.status === 404){
                        Swal.fire({
                            icon: 'error',
                            text: error.response.data.error,
                            timer: 2000,
                            timerProgressBar: true,
                            color: '#ffffff',
                            background: '#24283b'
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: error.response.data.error,
                            color: '#ffffff',
                            background: '#24283b'
                        });
                    }
                    
                });
        })

        async function collectItems(input){
            let dataID = input.value.length + 1;
            if(dataID > 1){
                await axios.get('/sale-collect-item/'+input.value)
                .then(function (response) {
                    if(response.status == 200){
                        listItemOption.classList.add('show')
                        listItemOption.innerHTML = response.data.data;
                    }
                })
                .catch(function (error) {
                    listItemOption.classList.add('show')
                    listItemOption.innerHTML = error.response.data.errors;
                })
            }else{
                listItemOption.classList.remove('show');
            }
        }

        async function setValue(data) {
            searchItemInput.setAttribute('data', data.getAttribute('data'))
            searchItemInput.value = data.innerHTML;
            listItemOption.classList.remove('show')
            listItemOption.innerHTML = '';

            //input to fill
            let itemCategory = document.querySelector('.item-category');
            let itemSubCategory = document.querySelector('.item-sub-category');
            let itemBarcode = document.querySelector('.item-barcode');
            let itemSell = document.querySelector('.item-sell');
            let itemQuantity = document.querySelector('.item-quantity');
            let itemDescription = document.querySelector('.item-description');


            await axios.get('/sales-collect-data/'+data.getAttribute('data'))
                .then(function (response) {
                    if(response.status == 200){
                        let item = response.data.data;
                        
                        itemCategory.value = item.category;
                        itemSubCategory.value = item.subCategory;
                        itemBarcode.value = item.barcode;
                        itemSell.value = item.sell;
                        itemQuantity.value = item.quantity == 0 ? 'Out of stock' : item.quantity
                        itemDescription.value = item.description;
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        icon: 'error',
                        text: error.response.data.error,
                        timer: 2000,
                        timerProgressBar: true,
                        color: '#ffffff',
                        background: '#24283b'
                    });
                })
        }

        async function getSalesList(){
            await axios.get('/get-sales-list')
                .then(function (response) {
                    if(response.status == 200){
                        showList.innerHTML = response.data.data;
                        document.querySelector('.limit').innerHTML = response.data.limit;
                        document.querySelector('.totalAmountToPay').innerHTML = response.data.totalAmount;
                        @if(Auth::user()->role == 'Admin')
                        document.querySelector('.item-discount').value = response.data.discount;
                        document.querySelector('.item-discount-text').innerHTML = response.data.discount;
                        document.querySelector('.item-totalAmountDiscounted').innerHTML = response.data.totalAmountDiscounted;
                        @endif
                    }
                })
                .catch(function (error) {
                    showList.innerHTML = error.response.data.error;
                    document.querySelector('.limit').innerHTML = error.response.data.limit;
                    document.querySelector('.totalAmountToPay').innerHTML = error.response.data.totalAmount;
                    @if(Auth::user()->role == 'Admin')
                    document.querySelector('.item-discount').value = error.response.data.discount;
                    document.querySelector('.item-discount-text').innerHTML = error.response.data.discount;
                    document.querySelector('.item-totalAmountDiscounted').innerHTML = error.response.data.totalAmountDiscounted;
                    @endif
                })
        }

        function deleteSalesList(data){
            const dataID = data.getAttribute('data');
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
                axios.get('/delete-sales-list/' + dataID)
                        .then(function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                                getSalesList();
                            }
                        })
                        .catch(function (error) {
                            if(error.response.status == 404){
                                Swal.fire({
                                    icon: 'error',
                                    text: error.response.statusText,
                                    timer: 2000,
                                    color: '#ffffff',
                                    background: '#24283b',
                                    timerProgressBar: true,
                                });
                            }
                        })
                }
            })
        }

        async function updateDeliveryStatus(data){
            const dataID = data.getAttribute('data');
            await axios.get('/update-sales-delivery-status/' + dataID)
                .then(function (response) {
                    if(response.status == 200){
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            text: response.data.message,
                            position: 'top-right',
                            showConfirmButton: false,
                            timer: 3000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        getSalesList()
                    }
                })
                .catch(function (error) {
                    if(error.response.status == 404){
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            text: error.response.statusText,
                            position: 'top-right',
                            showConfirmButton: false,
                            timer: 3000,
                            color: '#ffffff',
                            background: '#24283b',
                        });
                    }
                })
        }

        submit_list.addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            axios.post('/submit-sales-list', formData)
                .then((response) => {
                    if(response.status == 200){
                        Swal.fire({
                            icon: 'success',
                            text: response.data.message,
                            timer: 2000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                        });
                        getSalesList();
                    }
                })
                .catch(function (error) {
                    if(error.response.status == 422){
                        Swal.fire({
                            icon: 'error',
                            text: error.response.data.errors.form_number,
                            timer: 2000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                        });
                    }else if(error.response.status == 410){
                        Swal.fire({
                            icon: 'error',
                            text: error.response.data.error,
                            timer: 2000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                        });
                    }
                });
        })

        async function updateSalesDiscount(value){
            await axios.get('/update-sales-discount/'+value)
                .then(function (response) {
                    if(response.status == 200){
                        getSalesList()
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            text: response.data.message,
                            position: 'top-right',
                            showConfirmButton: false,
                            timer: 3000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        icon: 'error',
                        text: error.response.data.error,
                        timer: 2000,
                        timerProgressBar: true,
                        color: '#ffffff',
                        background: '#24283b'
                    });
                })
        }

    </script>
@endsection