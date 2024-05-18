@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management - Table Add
@stop
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Restaurants</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Table Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation">
                            <form method="post" action="{{ route('tables.store') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="mb-4 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Number
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"
                                                    placeholder="Enter Number.." name="number" required="">
                                                @error('number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter Number.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4 row">
                                            <label class="col-lg-4 col-form-label"
                                                for="validationCustom02">seating_configuration <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="seating_configuration"
                                                    id="validationCustom02" placeholder="seating_configuration"
                                                    required="">
                                                @error('seating_configuration')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter seating_configuration.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">capacity
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom08"
                                                    placeholder="capacity" name="capacity" required="">
                                                
                                                    <input type="hidden" value="{{$res_id}}" name="res_id">
                                                <div class="invalid-feedback">
                                                    @error('capacity')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                    Please enter capacity.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <style>
                                                .sub {
                                                    display: inline-block;
                                                }
                                            </style>
                                            <div class="mb-6 row sub " style="color: black">
                                                <div class="col-lg-8 ms-auto">

                                                    <button type="submit" style="width:250px;" name="action" value="more_add"
                                                        class="btn btn-primary">submit And add more</button>
                                                </div>
                                            </div>

                                            <div class="mb-6 row sub">
                                                <div class="col-lg-8 ms-auto">
                                                    <button type="submit" style="width:250px;" name="action" value="add_and_cancel"
                                                        class="btn btn-danger">submit And Cancel</button>
                                                </div>
                                            </div>
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
@endsection
@section('js')
@endsection
