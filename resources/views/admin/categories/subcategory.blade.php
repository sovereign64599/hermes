@extends('layouts.app')

@section('title', 'Sub Category')

@section('content')
    <div class="row categories">
        <div class="col-lg-3">
            <div class="card p-4 text-left sticky-top">
                <div class="card-header">
                    <h4 class="m-0 text-tertiary">Add Sub Category</h4>
                </div>
                <div class="card-body">
                    <form id="addSubCategory">
                        @csrf
                        @if($categories->count() > 0)
                            <input type="hidden" name="category_id">
                            <div class="form-group">
                                <label><small>Category</small></label>
                                <select class="form-select" name="category_name">
                                    <option value="" selected>Choose Category</option>
                                    @foreach($categories as $item)
                                        <option value="{{$item->category_name}}|{{$item->id}}">{{$item->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label><small>Sub Category Name</small></label>
                                <input type="text" name="sub_category_name" class="form-control" placeholder="Category name">
                            </div>
                            <div class="form-group">
                                <button class="btn text-light">Create Category</button>
                            </div>
                        @else 
                            <div class="form-group">
                                <label><small>Your Category List is empty, make sure you added a one or more to enable this.</small></label><br>
                                <a href="{{route('categories')}}" class="btn btn-sm text-light">Add Category</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card shadow mb-4 p-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="m-0 text-tertiary">Sub Category List</h4>
                    <div>
                        <input type="text" placeholder="Search category" oninput="filter(this)">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Sub Category Created</th>
                                    <th>Sub Category Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showSubCategory">
                                <tr>
                                    <td>No Item Found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- modal --}}
            <div class="modal fade" id="editSubCategory" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Update Sub Category</h5>
                        </div>
                        <div class="modal-body border-0">
                            <form id="updateSubCategory" class="modal-sub-category">
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
    const subCategoryForm = document.querySelector('#addSubCategory');
    const modalSubCategory = document.querySelector('.modal-sub-category');
    const updateSubCategory = document.querySelector('#updateSubCategory');
    var myModal = new bootstrap.Modal(document.getElementById('editSubCategory'), {
        // keyboard: false
    })
    getSubCategory();

    async function getSubCategory(){
        document.querySelector('#showSubCategory').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div></div>`;
        
        await axios.get('/get-sub-category')
            .then(function (response) {
                if(response.data.status == 200){
                    document.querySelector('#showSubCategory').innerHTML = `<tr><td>${response.data.data}</td></tr>`;
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    async function filter(input) {
        document.querySelector('#showSubCategory').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div></div>`;
        let incrementedLengthInput = input.value.length + 1;
        if(incrementedLengthInput > 2){
            await axios.get('/filter-sub-category/'+input.value)
            .then(function (response) {
                setTimeout(() => {
                    if(response.data.status == 200){
                        document.querySelector('#showSubCategory').innerHTML = response.data.data;
                    }else if(response.data.empty){
                        getSubCategory();
                    }
                }, 1000);
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
        }else{
            getSubCategory();
        }
    }

    subCategoryForm.addEventListener('submit', function(e){
        e.preventDefault();
        let formData = new FormData(this);
        axios.post('/create-sub-category', formData)
            .then((response) => {
                if(response.data.status == 200){
                    Swal.fire({
                        icon: 'success',
                        text: response.data.message,
                        color: '#ffffff',
                        background: '#24283b',
                    });
                    subCategoryForm.reset("reset");
                    getSubCategory();
                }else if(response.data.status == 422){
                    Swal.fire({
                        icon: 'error',
                        text: response.data.message,
                        color: '#ffffff',
                        background: '#24283b',
                    });
                }else{
                    Swal.fire({
                        text: response.data.message,
                        icon: 'warning',
                        color: '#ffffff',
                        background: '#24283b',
                        showCancelButton: false,
                        confirmButtonColor: '#d95650',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Reload'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }
            })
            .catch(function (error) {
                if(error){
                    if( error.response.status === 422 ) {
                        var errors = error.response.data.errors;
                        Swal.fire({
                            icon: 'error',
                            html: `${errors.sub_category_name}`,
                            color: '#ffffff',
                            background: '#24283b',
                        });
                    }else if( error.response.status === 404 ) {
                        Swal.fire({
                            text: 'Something went wrong. Please reload page.',
                            icon: 'warning',
                            color: '#ffffff',
                            background: '#24283b',
                            showCancelButton: false,
                            confirmButtonColor: '#d95650',
                            cancelButtonColor: '#858796',
                            confirmButtonText: 'Reload'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                }
            });
    })

    if(updateSubCategory){
        updateSubCategory.addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            axios.post('/update-sub-category', formData)
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
                    updateSubCategory.reset("reset");
                    myModal.hide()
                    getSubCategory();
                }else{
                    Swal.fire({
                        icon: 'error',
                        text: response.data.message,
                        color: '#ffffff',
                        background: '#24283b',
                    });
                }
            })
            .catch(function (error) {
                if(error){
                    if( error.response.status === 422 ) {
                        var errors = error.response.data.errors;
                        Swal.fire({
                            icon: 'error',
                            html: `${errors.sub_category_name}`,
                            color: '#ffffff',
                            background: '#24283b',
                        });
                    }
                }
            });
        })
    }

    async function editSubCategory(id){
        const dataID = id.getAttribute('data');
        await axios.get('/edit-sub-category/' + dataID)
            .then(function (response) {
                myModal.show()
                modalSubCategory.innerHTML = response.data;
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

    function deleteCategory(id){
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
            axios.get('/delete-sub-category/' + dataID)
                    .then(function (response) {
                        if(response.data.status == 200){
                            Swal.fire({
                                icon: 'success',
                                text: response.data.message,
                                timer: 2000,
                                color: '#ffffff',
                                background: '#24283b',
                                timerProgressBar: true,
                            });
                            getSubCategory();
                        }
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
        })
    }
</script>
@endsection
