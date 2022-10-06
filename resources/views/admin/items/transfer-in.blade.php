@extends('layouts.app')

@section('title', 'Transfer In')

@section('content')
    <div class="row transfer-in">
        <div class="col-lg-4">
            <div class="card text-left sticky-top p-4">
                <div class="card-header p-3">
                    <h4 class="m-0 text-tertiary">Add Items</h4>
                    <p class="text-light-400">Unlimited Items</p>
                </div>
                <div class="card-body pt-0">
                    <form id="transferIn" enctype="multipart/form-data">
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
                                    <label><small>Item Quantity</small></label>
                                    <input type="text" name="item_quantity" class="form-control" class="form-control" placeholder="Item Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item barcode</small></label>
                                    <input type="text" name="item_barcode" class="form-control" placeholder="Item Bar code" maxlength="6" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Cost</small></label>
                                    <input type="text" name="item_cost" class="form-control" placeholder="Cost" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Sell</small></label>
                                    <input type="text" name="item_sell" class="form-control" placeholder="Sell" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="item_img"><small>Item photo (Optional)</small></label>
                                    <input type="file" name="item_photo" class="form-control" id="item_img" placeholder="Cost">
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
                                <button class="btn btn-primary text-light">Reset Form</button>
                                <button class="btn text-light">Submit Items</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Items Available</h4>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm text-light"><i class="fas fa-file-import mr-2"></i><span>Import Items</span></button>
                            <button class="btn btn-sm text-light"><i class="fas fa-file-export mr-2"></i><span>Export Items</span></button>
                        </div>
                        <div>
                            <input type="text" placeholder="Search Item" oninput="filter(this)">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Quantity</th>
                                    <th>Bar Code</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th>Sell</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showItems">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- modal --}}
            <div class="modal fade" id="editItems" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Update Item</h5>
                        </div>
                        <div class="modal-body border-0">
                            <form id="updateItems" class="modal-item" enctype="multipart/form-data">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end modal --}}
        </div>  
    </div>
@endsection

@section('script')
    <script>
        const transferIn = document.querySelector('#transferIn');
        const updateItem = document.querySelector('#updateItems');
        const modalItem = document.querySelector('.modal-item');
        var myModal = new bootstrap.Modal(document.getElementById('editItems'), {
            // keyboard: false
        })
        getItems();

        async function getItems(){
            document.querySelector('#showItems').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            
            await axios.get('/get-items')
                .then(function (response) {
                    if(response.data.status == 200){
                        document.querySelector('#showItems').innerHTML = `<tr><td>${response.data.data}</td></tr>`;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
        }

        async function filter(input) {
            document.querySelector('#showItems').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            let incrementedLengthInput = input.value.length + 1;
            if(incrementedLengthInput > 2){
                await axios.get('/filter-items/'+input.value)
                .then(function (response) {
                    setTimeout(() => {
                        if(response.data.status == 200){
                            document.querySelector('#showItems').innerHTML = response.data.data;
                        }else if(response.data.empty){
                            getItems();
                        }
                    }, 1000);
                })
                .catch(function (error) {
                    console.log(error.response.data);
                })
            }else{
                getItems();
            }
        }

        transferIn.addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);
            axios.post('/store-items', formData)
                .then((response) => {
                    if(response.data.status == 200){
                        Swal.fire({
                            icon: 'success',
                            text: response.data.message
                        });
                        transferIn.reset("reset");
                        getItems();
                    }else if(response.data.status == 400){
                        Swal.fire({
                            text: response.data.message,
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
                            });
                        }
                    }
                });
        })

        async function collectSubCategory(data){
            var selectedOption = data.options[data.selectedIndex];
            var dataID = selectedOption.getAttribute('data');
            await axios.get('/collect-sub-categories/' + dataID)
                .then(function (response) {
                    if(response.data.status == 200){
                        document.querySelector('#item_sub_category').innerHTML = response.data.data;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
        }

        function updateExistItems(dataToUpdate) {
            axios.post('/update-exist-items', dataToUpdate)
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
                        transferIn.reset("reset");
                        getItems();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
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
                        myModal.hide()
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

        async function editItem(id){
            const dataID = id.getAttribute('data');
            await axios.get('/edit-items/' + dataID)
                .then(function (response) {
                    myModal.show()
                    modalItem.innerHTML = response.data;
                })
                .catch(function (error) {
                    if(error){
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
                    }
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
@endsection