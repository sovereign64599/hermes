@extends('layouts.app')

@section('title', 'Transfer In')

@section('content')
    <div class="row transfer-in">
        <div class="col-lg-3">
            <div class="card text-left">
                <div class="card-header pb-0">
                    <h6 class="m-0 text-tertiary">Add Items</h6>
                    <p class="text-light-400">Unlimited Items</p>
                </div>
                <div class="card-body pt-0">
                    <form action="">
                        <div class="row">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Item Name">
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select class="form-select">
                                        <option selected>Item Category</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <select class="form-select">
                                        <option selected>Item Category</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Item Quantity">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Item Bar code">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Cost">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Sell">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name=""rows="3" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="text-light" for="item_img">Item photo (Optional)</label>
                                <input type="file" class="form-control" id="item_img" placeholder="Cost">
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
                    <h6 class="m-0 text-tertiary">DataTables Example</h6>
                    <div class="d-flex py-2 gap-1">
                        <button class="btn text-light"><i class="fas fa-file-import mr-2"></i><span>Import Items</span></button>
                        <button class="btn text-light"><i class="fas fa-file-export mr-2"></i><span>Export Items</span></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
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
                            <tbody>
                                <tr>
                                    <td>
                                        <img class="img-fluid item-photo" src="https://www.thespruce.com/thmb/lbeqtgmmoRpaeMtbCH568V-8CBI=/3888x2592/filters:no_upscale():max_bytes(150000):strip_icc()/c-pvc-and-u-pvc-fittings-172717498-5ac54ef1a474be00365ae8e6.jpg" alt="img1">
                                    </td>
                                    <td>Office Manager</td>
                                    <td>Office Manager</td>
                                    <td>London</td>
                                    <td>30</td>
                                    <td>2008/12/19</td>
                                    <td>$90,560</td>
                                    <td>$90,560</td>
                                    <td>$90,560</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="#" class="btn text-light">Edit</a>
                                            <a href="#" class="btn text-light">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img class="img-fluid item-photo" src="https://www.thespruce.com/thmb/lbeqtgmmoRpaeMtbCH568V-8CBI=/3888x2592/filters:no_upscale():max_bytes(150000):strip_icc()/c-pvc-and-u-pvc-fittings-172717498-5ac54ef1a474be00365ae8e6.jpg" alt="img1">
                                    </td>
                                    <td>Support Lead</td>
                                    <td>Support Lead</td>
                                    <td>Edinburgh</td>
                                    <td>22</td>
                                    <td>2013/03/03</td>
                                    <td>$342,000</td>
                                    <td>$342,000</td>
                                    <td>$342,000</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="#" class="btn text-light">Edit</a>
                                            <a href="#" class="btn text-light">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img class="img-fluid item-photo" src="https://www.thespruce.com/thmb/lbeqtgmmoRpaeMtbCH568V-8CBI=/3888x2592/filters:no_upscale():max_bytes(150000):strip_icc()/c-pvc-and-u-pvc-fittings-172717498-5ac54ef1a474be00365ae8e6.jpg" alt="img1">
                                    </td>
                                    <td>Regional Director</td>
                                    <td>San Francisco</td>
                                    <td>36</td>
                                    <td>2008/10/16</td>
                                    <td>$470,600</td>
                                    <td>$470,600</td>
                                    <td>$470,600</td>
                                    <td>$470,600</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="#" class="btn text-light">Edit</a>
                                            <a href="#" class="btn text-light">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection