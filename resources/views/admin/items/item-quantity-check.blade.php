@extends('layouts.app')

@section('title', 'Item Quantity checker')

@section('content')
    <div class="row pages">
        <div class="col-lg-3">
            <div class="card text-left sticky-top p-2">
                <div class="card-body pt-0">
                    <div class="row">
                        @if($getSubCategories->count() > 0)
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><small>Item Category</small></label>
                                <select class="form-select" id="item_category" onchange="checkSubCategory(this)">
                                    <option value="" selected>Select Category</option>
                                    @foreach($getSubCategories as $category)
                                        <option data="{{$category->category_id}}" value="{{$category->category_name}}">{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><small>Item Sub Category</small></label>
                                <select class="form-select" id="item_sub_category" id="item_sub_category" onchange="subCategoryChange()">
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
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><small>Item Barcode</small></label>
                                <input type="text" id="code" class="form-control" placeholder="Enter barcode" minlength="12" maxlength="12" oninput="this.value = this.value.replace(/[^0-9-.]/g, ''); setValue()">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><small>Item Description</small></label>
                                <input type="text" id="description" class="form-control" placeholder="Enter description" oninput="setValue()">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Items Available</h4>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-light">10 of <span id="totalItems"></span></small>
                        <ul class="pagination" id="pagination_link">

                        </ul>
                    </div>
                </div>
            </div>
        </div>  
    </div>

    {{-- view item modal --}}
    <div class="modal fade" id="viewItemsModal" tabindex="-1" aria-labelledby="viewItemLabel" aria-hidden="true">
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
        const paginationLink = document.querySelector('#pagination_link')
        const totalItems = document.querySelector('#totalItems')
        // // modal init
        var viewItemModal = new bootstrap.Modal(document.getElementById('viewItemsModal'))
        filterItems(1);
        
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

        async function filterItems(page){
            document.querySelector('#showItems').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;
            let data = {
                _token: '{{csrf_token()}}',
                category: document.querySelector('#item_category').value,
                subCategory: document.querySelector('#item_sub_category').value,
                code: document.querySelector('#code').value,
                description: document.querySelector('#description').value,
                page: page,
            }

            await axios.post('/filter-items-available', data)
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#showItems').innerHTML = response.data.data;
                        if(response.data.pagination){
                            let pages = response.data.pagination.links;
                            paginationLink.innerHTML ='';
                            pages.forEach(function(item){
                                if(!isNaN(item.label)){
                                    totalItems.innerHTML = response.data.pagination.total;
                                    paginationLink.innerHTML += `<li style="cursor:pointer;" onclick="filterItems(${item.label})" class="page-item ${item.active ? 'active' : '' } "><a class="page-link" style="background-color: #1a1e29;">${item.label}</a></li>`;
                                }
                            });
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error.response);
                    document.querySelector('#showItems').innerHTML = `<tr><td>${error.response.data.errors}</td></tr>`;
                    paginationLink.innerHTML ='';
                    totalItems.innerHTML = 0;
                })
        }

        function subCategoryChange(){
            filterItems();
        }

        function setValue(){
            filterItems();
        }

        async function checkSubCategory(data){
            var selectedOption = data.options[data.selectedIndex];
            var dataID = selectedOption.getAttribute('data');

            await axios.get('/check-sub-categories/' + dataID)
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#item_sub_category').innerHTML = response.data.data;
                        filterItems();
                    }
                })
                .catch(function (error) {
                    document.querySelector('#item_sub_category').innerHTML = error.response.data.errors;
                })
        }

    </script>
@endsection