@extends('layouts.app')

@section('title', 'Transfer In')

@section('content')
    <div class="row pages">
        <div class="col-lg-4">
            <form id="add_list">
                <div class="card text-left sticky-top p-2">
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="form-group">
                                <label><small>Item Name</small></label>
                                <div class="auto-sugguestion">
                                    <input type="search" class="form-control" id="search_item" placeholder="Search Item Name"  oninput="collectItemNames(this)" autocomplete="false">
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
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item barcode</small></label>
                                    <input readonly class="form-control item-barcode" placeholder="Item Bar code" maxlength="6" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Cost</small></label>
                                    <input readonly class="form-control item-cost" placeholder="Item cost" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Sell</small></label>
                                    <input readonly class="form-control item-sell" placeholder="Item Sell" disabled>
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
                                    <label><small>Added Quantity</small></label>
                                    <input type="text" id="item_added_quantity" class="form-control bg-black" class="form-control" placeholder="Add Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><small>Item Description</small></label>
                                <textarea readonly rows="3" class="form-control item-description" placeholder="Description" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label><small>Add Notes (Optional)</small></label>
                                <textarea id="item_notes" rows="3" class="form-control bg-black" value="No Notes" placeholder="Add notes"></textarea>
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
                <div class="card shadow p-2">
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
                                        <th>Item Category</th>
                                        <th>Sub Category</th>
                                        <th>Item Barcode</th>
                                        <th>Current Quantity</th>
                                        <th>Added Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="showList">
                                    {{-- show list here --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <p><small class="text-tertiary limit">Items (0/10)</small></p>
                        <button class="btn text-light">Submit</button>
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
            getList()
        }

        addList.addEventListener('submit', function(e){
            e.preventDefault();
            let id = document.querySelector('#search_item').getAttribute('data')
            let added_quantity = document.querySelector('#item_added_quantity').value
            let notes = document.querySelector('#item_notes').value
            let formData = {
                id: id,
                addedQty: added_quantity,
                notes: notes
            };
            axios.post('/add-list', formData)
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
                        getList()
                    }
                })
                .catch(function (error) {
                    
                    if (error.response.status === 422) {
                        Swal.fire({
                            icon: 'error',
                            text: error.response.data.errors.addedQty,
                            timer: 2000,
                            timerProgressBar: true,
                            color: '#ffffff',
                            background: '#24283b'
                        });
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
                            timer: 2000,
                            timerProgressBar: true,
                            color: '#ffffff',
                            background: '#24283b'
                        });
                    }
                    
                });
        })

        async function collectItemNames(input){
            let dataID = input.value.length + 1;
            if(dataID > 1){
                await axios.get('/collect-item-names/'+input.value)
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
            let itemCost = document.querySelector('.item-cost');
            let itemSell = document.querySelector('.item-sell');
            let itemQuantity = document.querySelector('.item-quantity');
            let itemDescription = document.querySelector('.item-description');


            await axios.get('/collect-data/'+data.getAttribute('data'))
                .then(function (response) {
                    if(response.status == 200){
                        let item = response.data.data;
                        
                        itemCategory.value = item.category;
                        itemSubCategory.value = item.subCategory;
                        itemBarcode.value = item.barcode;
                        itemCost.value = item.cost;
                        itemSell.value = item.sell;
                        itemQuantity.value = item.quantity
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

        async function getList(){
            await axios.get('/get-list')
                .then(function (response) {
                    if(response.status == 200){
                        showList.innerHTML = response.data.data;
                        document.querySelector('.limit').innerHTML = response.data.limit;
                    }
                })
                .catch(function (error) {
                    showList.innerHTML = error.response.data.error;
                    document.querySelector('.limit').innerHTML = error.response.data.limit;
                })
        }

        function deleteList(data){
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
                axios.get('/delete-list/' + dataID)
                        .then(function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                                getList();
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

        submit_list.addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            axios.post('/submit-list', formData)
                .then((response) => {
                    if(response.status == 200){
                        Swal.fire({
                            icon: 'success',
                            text: response.data.message,
                            timer: 2000,
                            timerProgressBar: true,
                        });
                        getList();
                    }
                })
                .catch(function (error) {
                    if(error.response.status == 422){
                        Swal.fire({
                            icon: 'error',
                            text: error.response.statusText,
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

    </script>
@endsection