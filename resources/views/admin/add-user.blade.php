@extends('layouts.app')

@section('title', 'Add Users')

@section('content')
    <div class="row transfer-in">
        <div class="col-lg">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Add Users</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('store.user')}}" method="POST">
                        @csrf 
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control  @error('firstname') is-invalid @enderror" value="{{old('firstname')}}" placeholder="First name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" name="middlename" class="form-control  @error('middlename') is-invalid @enderror" value="{{old('middlename')}}" placeholder="Middle name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control  @error('lastname') is-invalid @enderror" value="{{old('lastname')}}" placeholder="Last name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror" value="{{old('email')}}" placeholder="Email Address">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control  @error('gender') is-invalid @enderror">
                                        <option value="" selected>Choose Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control  @error('role') is-invalid @enderror">
                                        <option value="" selected>Choose Role</option>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" name="password" class="form-control  @error('password') is-invalid @enderror" value="{{old('password')}}" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Password Confirm</label>
                                    <input type="text" name="password_confirmation" class="form-control  @error('password_confirmation') is-invalid @enderror" value="{{old('password_confirmation')}}" placeholder="Password confirmation">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn bg-tertiary text-light">Add this user</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
@endsection
