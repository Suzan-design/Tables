@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management - Profile
@stop
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)">App</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Profile</a>
                </li>
            </ol>
        </div>
        <!-- row -->

        <div class="row">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-tab">
                            <div class="custom-tab-1">
                                <ul class="nav nav-tabs">

                                    <li class="nav-item">
                                        <a href="#profile-settings" data-bs-toggle="tab"
                                            class="nav-link active show">Setting</a>
                                    </li>
                                </ul>
                                <div class="tab-content">

                                    <div id="profile-settings" class="tab-pane fade active show">
                                        <div class="pt-3">
                                            <div class="settings-form">
                                                <h4 class="text-primary">Account</h4>
                                                <form method="post" action="{{ route('update_profile_staff') }}"
                                                    autocomplete="off" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="mb-4 col-md-4">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" placeholder="Email"
                                                                value="{{ $user->email }}"name="email"
                                                                class="form-control" />

                                                            @error('email')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-4 col-md-4">
                                                            <label class="form-label">Password</label>
                                                            <input type="password" placeholder="*************"
                                                                class="form-control"name="password" />
                                                            @error('password')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-4 col-md-4">
                                                            <label class="form-label">Password Confirmation</label>
                                                            <input type="password" placeholder="*************"
                                                                name="password_confirmation" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="mb-4 col-md-4">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" placeholder="Name"
                                                                value="{{ $user->name }}" class="form-control"
                                                                name="name" required />
                                                            @error('name')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-4 col-md-4">
                                                            <label class="form-label">Phone</label>
                                                            <input type="text" placeholder="phone" name="phone"
                                                                value="{{ $user->phone }}" class="form-control"
                                                                name="phone" required />
                                                            @error('Phone')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-4 col-md-4">
                                                            <label class="form-label">Role</label>
                                                            <input type="text" value="{{ $user->roleName }}"
                                                                class="form-control" disabled />
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">
                                                        Confirm
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
