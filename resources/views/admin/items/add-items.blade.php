@extends('layouts.app')

@section('title', 'Add Items')

@section('content')
    <div class="row pages">
        <div class="col-lg-4">
            <div class="card text-left sticky-top p-2">
                <div class="card-header p-3">
                    <h4 class="m-0 text-tertiary">Add Items</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="{{route('store.items')}}" method="POST" enctype="multipart/form-data">
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
                                <button class="btn text-light">Add Items</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow p-2">
                <div class="card-header">
                    <h4 class="text-tertiary">Items Available</h4>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-1">
                            <form id="importForm" action="{{route('import.items')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" class="d-none" id="import" onchange="document.getElementById('importForm').submit()">
                            </form>
                            <button type="button" role="button" class="btn btn-sm text-light" onclick="document.getElementById('import').click();"><i class="fas fa-file-import mr-2"></i><span>Import Items</span></button>
                            <a href="{{route('export.items')}}" class="btn btn-sm text-light d-flex align-items-center"><i class="fas fa-file-export mr-2"></i><span>Export Items</span></a>
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
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Bar Code</th>
                                    <th>Cost</th>
                                    <th>Sell</th>
                                    <th>Quantity</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showItems">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>

    {{-- view item modal --}}
    <div class="modal fade" id="viewItem" tabindex="-1" aria-labelledby="viewItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-body border-0 text-center" id="viewItemsContent">
                
            </div>
          </div>
        </div>
      </div>
@endsection

@section('script')
    <script>
        const viewItemsContent = document.querySelector('#viewItemsContent')
        getItems();
        // modal init
        var viewItemModal = new bootstrap.Modal(document.getElementById('viewItem'))
        async function viewItem(item){
            const dataID = item.getAttribute('data');

            await axios.get(`/view-items/${dataID}`)
                .then(function (response) {
                    if(response.status == 200){
                        viewItemsContent.innerHTML = response.data.data
                        viewItemModal.show()
                    }
                })
                .catch(function (error) {
                    document.querySelector('#showItems').innerHTML = `<tr><td>${error.response.data.errors}</td></tr>`;
                })
        }
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
        async function filter(input) {
            document.querySelector('#showItems').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            let incrementedLengthInput = input.value.length + 1;
            if(incrementedLengthInput > 1){
                await axios.get('/filter-items/'+input.value)
                .then(function (response) {
                    setTimeout(() => {
                        if(response.status == 200){
                            document.querySelector('#showItems').innerHTML = response.data.data;
                        }
                    }, 1000);
                })
                .catch(function (error) {
                    document.querySelector('#showItems').innerHTML = error.response.data.errors;
                })
            }else{
                getItems();
            }
        }
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
        function deleteItem(item){
            const dataID = item.getAttribute('data');
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
                    window.location.replace(`/delete-items/${dataID}`);
                }
            })
        }
    </script>
@endsection