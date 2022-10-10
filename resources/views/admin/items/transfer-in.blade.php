@extends('layouts.app')

@section('title', 'Transfer In')

@section('content')
    <div class="row transfer-in">
        <div class="col-lg-4">
            <div class="card text-left sticky-top p-4">
                <div class="card-body pt-0">
                    @csrf
                    <div class="row">
                        <div class="form-group">
                            <label><small>Item Name</small></label>
                            <input type="text" name="item_name" class="form-control" placeholder="Item Name">
                        </div>
                        @if($getSubCategories->count() > 0)
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><small>Item Category</small></label>
                                <select class="form-select" name="item_category" onchange="collectSubCategory(this)">
                                    <option value="" selected>Select Category</option>
                                    @foreach($getSubCategories as $category)
                                        <option data="{{$category->category_id}}" value="{{$category->category_name}}">{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><small>Item Sub Category</small></label>
                                <select class="form-select" name="item_sub_category" id="item_sub_category">
                                    <option value="" selected>Choose Category first</option>
                                </select>
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
                                <input type="text" class="form-control" placeholder="Item Bar code" maxlength="6" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" disabled>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><small>Item Cost</small></label>
                                <input type="text" name="item_cost" class="form-control" placeholder="Cost" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label><small>Item Sell</small></label>
                                <input type="text" name="item_sell" class="form-control" placeholder="Sell" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><small>Current Quantity</small></label>
                                <input type="text" name="item_quantity" class="form-control" class="form-control" placeholder="Current Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><small>Added Quantity</small></label>
                                <input type="text" name="item_quantity" class="form-control" class="form-control" placeholder="Added Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><small>Item Description</small></label>
                            <textarea name="item_description" rows="3" class="form-control" placeholder="Description"></textarea>
                        </div>
                        <div class="form-group">
                            <label><small>Add Notes (Optional)</small></label>
                            <textarea name="item_notes" rows="3" class="form-control" value="No Notes" placeholder="Add notes"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn text-light">Add Items</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <form id="transferIn" enctype="multipart/form-data">
                <div class="card shadow p-4">
                    <div class="card-header">
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Item Category</th>
                                        <th>Sub Category</th>
                                        <th>Item Barcode</th>
                                        <th>Item Cost</th>
                                        <th>Item Sell</th>
                                        <th>Current Quantity</th>
                                        <th>Added Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($items->count() > 0)
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{$item->item_name}}</td>
                                                <td>{{$item->item_category}}</td>
                                                <td>{{$item->item_sub_category}}</td>
                                                <td>{{$item->item_barcode}}</td>
                                                <td>{{$item->item_cost}}</td>
                                                <td>{{$item->item_sell}}</td>
                                                <td>
                                                    @if($item->item_quantity == 0)
                                                    <small class="text-danger">Out of Stock</small>
                                                    @else
                                                    {{$item->item_quantity}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->item_quantity == 0)
                                                    <small class="text-danger">Out of Stock</small>
                                                    @else
                                                    {{$item->item_quantity}}
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
                    <div class="card-footer bg-transparent border-0">
                        <p class="text-tertiary">Items (3/10)</p>
                        <button class="btn text-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

{{-- @section('script')
    <script>
        const transferIn = document.querySelector('#transferIn');
        const updateItem = document.querySelector('#updateItems');
        getItems();

        async function getItems(){
            document.querySelector('#showItems').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            
            await axios.get('/get-items')
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#showItems').innerHTML = `<tr><td>${response.data.data}</td></tr>`;
                    }
                })
                .catch(function (error) {
                    document.querySelector('#showItems').innerHTML = `<tr><td>${error.response.data.errors}</td></tr>`;
                })
        }

        transferIn.addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);
            axios.post('/store-items', formData)
                .then((response) => {
                    if(response.status == 200){
                        Swal.fire({
                            icon: 'success',
                            text: response.data.message
                        });
                        transferIn.reset("reset");
                        getItems();
                    }
                })
                .catch(function (error) {
                    if(error){
                        if( error.response.status === 422 ) {
                            var errors = error.response.data.errors;
                            errorMsg = [
                                errors.item_name, 
                                errors.item_barcode, 
                                errors.item_category, 
                                errors.item_sub_category,
                                errors.item_quantity,
                                errors.item_description,
                                errors.item_cost,
                                errors.item_sell,
                                errors.item_notes
                            ]
                            let errMessage = [];
                            errorMsg.forEach(function(item, key){
                                errMessage += `<li style="list-style:none;">${item == null ? '' : item}</li>`;
                            })
                            Swal.fire({
                                icon: 'error',
                                html: `<ul>${errMessage}</ul>`,
                            });
                        }else{
                            Swal.fire({
                                text: error.response.data.error,
                                icon: 'info',
                                color: '#ffffff',
                                background: '#24283b',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Update'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    let dataToUpdate = new FormData(document.querySelector('#transferIn'));
                                    updateExistItems(dataToUpdate)
                                }
                            })
                        }
                    }
                });
        })

        async function collectSubCategory(data){
            var selectedOption = data.options[data.selectedIndex];
            var dataID = selectedOption.getAttribute('data');
            await axios.get('/collect-sub-categories/' + dataID)
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#item_sub_category').innerHTML = response.data.data;
                    }
                })
                .catch(function (error) {
                    document.querySelector('#item_sub_category').innerHTML = error.response.data.errors;
                })
        }

        

        if(updateItem){
            updateItem.addEventListener('submit', function(e){
                e.preventDefault();
                let formData = new FormData(this);

                axios.post('/update-items', formData)
                .then((response) => {
                    if(response.data.status == 200){
                        Swal.fire({
                            icon: 'success',
                            text: response.data.message,
                            timer: 2000,
                            color: '#ffffff',
                            background: '#24283b',
                            timerProgressBar: true,
                        });
                        updateItem.reset("reset");
                        getItems();
                    }
                })
                .catch(function (error) {
                    if(error){
                        if( error.response.status === 422 ) {
                            var errors = error.response.data.errors;
                            errorMsg = [
                                errors.item_name, 
                                errors.item_barcode, 
                                errors.item_category, 
                                errors.item_sub_category,
                                errors.item_quantity,
                                errors.item_description,
                                errors.item_cost,
                                errors.item_sell,
                                errors.item_notes,
                                errors.item_photo,
                            ]
                            let errMessage = [];
                            errorMsg.forEach(function(item, key){
                                errMessage += `<li style="list-style:none;">${item == null ? '' : item}</li>`;
                            })
                            Swal.fire({
                                icon: 'error',
                                html: `<ul>${errMessage}</ul>`,
                                timer: 2000,
                                color: '#ffffff',
                                background: '#24283b',
                                timerProgressBar: true,
                            });
                        }
                        if(error.response.status == 404){
                            Swal.fire({
                                icon: 'error',
                                text: error.response.statusText,
                                timer: 2000,
                                color: '#ffffff',
                                background: '#24283b',
                                timerProgressBar: true,
                            });
                            getItems();
                        }
                    }
                });
            })
        }

        function deleteItem(id){
            const dataID = id.getAttribute('data');
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
                axios.get('/delete-items/' + dataID)
                        .then(function (response) {
                            if(response.data.status == 200){
                                Swal.fire({
                                    icon: 'success',
                                    text: response.data.message,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                                getItems();
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
                                getItems();
                            }
                        })
                }
            })
        }

    </script>
@endsection --}}