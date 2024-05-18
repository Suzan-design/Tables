@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/calender.css') }}" rel="stylesheet">
@endsection
@section('title')
    Restaurants Management - Achievement Create
@stop
@section('content')
    <link href="{{ URL::asset('dashboard/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
    <style>
        select {
            width: 20em;
        }
    </style>
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Achievement</a></li>
            </ol>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    {{-- Loop through each error and display it --}}
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Achievement Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation">
                            <form id="filter-form" method="post" action="{{ route('invitations.store') }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="mb-6 col-md-6">
                                                <label class="form-label">Title</label>
                                                <input type="text" name="title"class="form-control" required>
                                                @error('title')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-6 col-md-6">
                                                <label class="form-label">Coupons</label>
                                                <input type="text" name="coupons"class="form-control" required>
                                                @error('coupons')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-12 col-md-12">
                                                <label class="form-label">Description</label>
                                                <textarea type="text" row="8" name="description"class="form-control" required> </textarea>
                                                @error('description')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-4 col-md-4">
                                                <label class="form-label">Type</label>
                                                <select class="default-select wide form-control" name="type"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    <option value="invitations">Invitations of friends</option>
                                                    <option value="reviews">Restaurant Reviews</option>
                                                    <option value="reservations">Number of reservations</option>
                                                </select>
                                                @error('type')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-4 col-md-4">
                                                <label class="form-label">Target</label>
                                                <input type="number" name="target"class="form-control" required>
                                                @error('target')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-4 col-md-4">
                                                <label class="form-label">Expire.days</label>
                                                <input type="number"name="expire" class="form-control" required>
                                                @error('expire')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-4 col-md-4">
                                                <label class="form-label">Discount.%</label>
                                                <input type="number" name="discount" class="form-control" required>
                                                @error('discount')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-4 col-md-4">
                                                <label class="form-label">limit</label>
                                                <input type="number" name="limit" class="form-control" required>
                                                @error('limit')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-4 col-md-4">
                                                <label class="form-label">image</label>
                                                <input type="file" name="image" class="form-control" required>
                                                @error('image')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                        </div>
                                    </div>

                                </div>

                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="mb-6 col-md-6" style="color: black">
                                            <div class="col-lg-24 ms-auto">
                                                <button type="submit" style="width:250px;" name="action" value="more_add"
                                                    class="btn btn-primary">submit And add more</button>
                                            </div>
                                        </div>
                                        <div class="mb-6 col-md-6">
                                            <div class="col-lg-24 ms-auto">
                                                <button type="submit" style="width:250px;" name="action" value="add_and_cancel"
                                                    class="btn btn-danger">submit And Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous">
    </script>
    <script src="{{ URL::asset('dashboard/js/multiselect-dropdown.js') }} "></script>



    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ URL::asset('dashboard/js/plugins-init/nouislider-init.js') }} "></script>

@endsection
@section('js')
@endsection
