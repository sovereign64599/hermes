@extends('layouts.app')

@section('title', 'Category')

@section('content')
    <div class="row categories">
        <div class="col-lg-3">
            <div class="card text-left sticky-top p-4">
                <div class="card-header">
                    <h4 class="m-0 text-tertiary">Add Category</h4>
                    <small class="text-light-400"><strong>Note:</strong> To Enable Sub Category, You need to add Category first.</small>
                </div>
                <div class="card-body pt-0">
                    <form id="addCategory">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label><small>Category Name</small></label>
                                <input type="text" name="category_name" class="form-control" placeholder="Category name">
                            </div>
                            <div class="form-group">
                                <label><small>Category Description (Optional)</small></label>
                                <textarea name="category_description" class="form-control" rows="3" placeholder="write.."></textarea>
                            </div>
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
            <div class="card shadow mb-4 p-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="m-0 text-tertiary">Category List</h4>
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
                                    <th>Category Description</th>
                                    <th>Category Created</th>
                                    <th>Category Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showCategory">
                                <tr>
                                    <td>No Item Found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- modal --}}
            <div class="modal fade" id="editCategory" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Update Category</h5>
                        </div>
                        <div class="modal-body border-0">
                            <form id="updateCategory" class="modal-category">
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
    const categoryForm = document.querySelector('#addCategory');
    const modalCategory = document.querySelector('.modal-category');
    const updateCategory = document.querySelector('#updateCategory');
    var myModal = new bootstrap.Modal(document.getElementById('editCategory'), {
        // keyboard: false
    })
    getCategory();

    async function getCategory(){
        document.querySelector('#showCategory').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div></div>`;
        
        await axios.get('/get-category')
            .then(function (response) {
                if(response.data.status == 200){
                    document.querySelector('#showCategory').innerHTML = `<tr><td>${response.data.data}</td></tr>`;
                }
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    async function filter(input) {
        document.querySelector('#showCategory').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div></div>`;
        let incrementedLengthInput = input.value.length + 1;
        if(incrementedLengthInput > 2){
            await axios.get('/filter-category/'+input.value)
            .then(function (response) {
                setTimeout(() => {
                    if(response.data.status == 200){
                        document.querySelector('#showCategory').innerHTML = response.data.data;
                    }else if(response.data.empty){
                        getCategory();
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
            getCategory();
        }
    }

    categoryForm.addEventListener('submit', function(e){
        e.preventDefault();
        let formData = new FormData(this);
        axios.post('/create-category', formData)
            .then((response) => {
                if(response.data.status == 200){
                    Swal.fire({
                        icon: 'success',
                        text: response.data.message,
                        color: '#ffffff',
                        background: '#24283b',
                    });
                    categoryForm.reset("reset");
                    getCategory();
                }
            })
            .catch(function (error) {
                if(error){
                    if( error.response.status === 422 ) {
                        var errors = error.response.data.errors;
                        Swal.fire({
                            icon: 'error',
                            html: `${errors.category_name}`,
                            color: '#ffffff',
                            background: '#24283b',
                        });
                    }
                }
            });
    })

    if(updateCategory){
        updateCategory.addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            axios.post('/update-category', formData)
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
                    updateCategory.reset("reset");
                    myModal.hide()
                    getCategory();
                }else{
                    Swal.fire({
                        icon: 'error',
                        text: response.data.message,
                        timer: 2000,
                        color: '#ffffff',
                        background: '#24283b',
                        timerProgressBar: true,
                    });
                }
            })
            .catch(function (error) {
                if(error){
                    if( error.response.status === 422 ) {
                        var errors = error.response.data.errors;
                        Swal.fire({
                            icon: 'error',
                            html: `${errors.category_name}`,
                            color: '#ffffff',
                            background: '#24283b',
                        });
                    }
                }
            });
        })
    }

    async function editCategory(id){
        const dataID = id.getAttribute('data');
        await axios.get('/edit-category/' + dataID)
            .then(function (response) {
                myModal.show()
                modalCategory.innerHTML = response.data;
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
            axios.get('/delete-category/' + dataID)
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
                            getCategory();
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
