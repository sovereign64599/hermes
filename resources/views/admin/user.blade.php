@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="row pages">
        <div class="col-lg">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Users</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->count() > 0)
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{$user->firstname}}</td>
                                            <td>{{$user->middlename}}</td>
                                            <td>{{$user->lastname}}</td>
                                            <td>{{$user->created_at->diffForHumans()}}</td>
                                            <td>
                                                <a href="{{route('edit.user', $user->id)}}" class="btn btn-primary">Edit</a>
                                                <a href="#" onclick="deleteUser()" class="btn btn-primary">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else 
                                <tr><td>No User found</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
@endsection

@if($users->count() > 0)
@section('script')
    <script>
        function deleteUser(){
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
                    window.location.replace("{{route('delete.user', $user->id)}}");
                }
            })
        }
    </script>
@endsection
@endif