@extends('layouts.app')

@section('title', 'Transfer In')

@section('content')
    <div class="row transfer-in">
        <div class="col-lg-3">
            <div class="card text-left sticky-top">
                <div class="card-header p-3">
                    <h4 class="m-0 text-tertiary">Add Category</h4>
                    <small class="text-light-400"><strong>Note:</strong> To Enable Sub Category, You need to add Category first.</small>
                </div>
                <div class="card-body pt-0">
                    <form id="transferIn" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="category_name" class="form-control" placeholder="Category name">
                            </div>
                            <div class="form-group">
                                <label>Category Description</label>
                                <textarea name="category_description" class="form-control" rows="3"></textarea>
                            </div>
                            {{-- <div class="col-lg-6">
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
                            </div> --}}
                            <div class="form-group">
                                <button class="btn btn-primary text-light">Reset Form</button>
                                <button class="btn text-light">Create Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-header p-3">
                    <h4 class="m-0 text-tertiary">Category List</h4>
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
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Category Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showItems">
                                <tr>
                                    <td>No Item Found</td>
                                </tr>
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
