@extends('layouts.app')

@section('title', 'Item Quantity checker')

@section('content')
    <div class="row pages">
        <div class="col-lg-3">
            <form id="add_list">
                <div class="card text-left sticky-top p-2">
                    <div class="card-body pt-0">
                        <div class="row">
                            @if($getSubCategories->count() > 0)
                            <div class="col-lg-12">
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
                            <div class="col-lg-12">
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
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><small>Item barcode / Description</small></label>
                                    <input type="text" name="item_barcode" class="form-control" placeholder="Enter barcode or description" minlength="8" maxlength="8" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                   ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing ongoing 
                </div>
            </div>
        </div>
    </div>
@endsection
