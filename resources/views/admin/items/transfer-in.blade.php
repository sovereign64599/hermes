@extends('layouts.app')

@section('title', 'Transfer In')

@section('content')
    <div class="row transfer-in">
        <div class="col-lg-3">
            <div class="card text-left sticky-top">
                <div class="card-header pb-0">
                    <h4 class="m-0 text-tertiary">Add Items</h4>
                    <p class="text-light-400">Unlimited Items</p>
                </div>
                <div class="card-body pt-0">
                    <form id="transferIn" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <input type="text" name="item_name" class="form-control" placeholder="Item Name">
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select class="form-select" name="item_category">
                                        <option value="" selected>Item Category</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select class="form-select" name="item_sub_category">
                                        <option value="" selected>Item Category</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" name="item_quantity" class="form-control" placeholder="Item Quantity">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" name="item_barcode" class="form-control" value="{{str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)}}" placeholder="Item Bar code">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" name="item_cost" class="form-control" placeholder="Cost">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" name="item_sell" class="form-control" placeholder="Sell">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="item_description" rows="3" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="text-light" for="item_img">Item photo (Optional)</label>
                                <input type="file" name="item_photo" class="form-control" id="item_img" placeholder="Cost">
                            </div>
                            <div class="form-group">
                                <label class="text-light">Add Notes (Optional)</label>
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
        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-header pb-0 pt-4">
                    <h4 class="m-0 text-tertiary">Items Available</h4>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex py-2 gap-1">
                            <button class="btn text-light"><i class="fas fa-file-import mr-2"></i><span>Import Items</span></button>
                            <button class="btn text-light"><i class="fas fa-file-export mr-2"></i><span>Export Items</span></button>
                        </div>
                        <div class="py-2">
                            <input type="text" placeholder="Search Item" oninput="filter(this)">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
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
                {{-- <div class="card-footer bg-transparent border-0">
                    <button class="btn text-light" id="nextPage" onclick="nextPage(this)">Next</button>
                </div> --}}
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
                    document.querySelector('#showItems').innerHTML = response.data.data;
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

        function updateExistItems(dataToUpdate) {
            axios.post('/update-exist-items', dataToUpdate)
                .then((response) => {
                    if(response.data.status == 200){
                        Swal.fire({
                            icon: 'success',
                            text: response.data.message,
                            timer: 2000,
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
                                timerProgressBar: true,
                            });
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
                            myModal.show()
                            modalItem.innerHTML = `<div class="modal-body text-center"><img class="img-fluid" src="{{asset('img/no_item.svg')}}"></img></div>`;
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
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
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
                            if(error){
                                if(error.response.status == 404){
                                    myModal.show()
                                    modalItem.innerHTML = `<div class="modal-body text-center"><img class="img-fluid" src="{{asset('img/no_item.svg')}}"></img></div>`;
                                }
                            }
                        })
                }
            })
        }

    </script>
@endsection