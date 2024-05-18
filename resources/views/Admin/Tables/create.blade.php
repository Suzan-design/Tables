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
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
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
                                    <div class="col-xl-6">
                                        <div class="mb-6 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Number
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"
                                                    placeholder="Enter Number.." name="number" required="">
                                                <div class="invalid-feedback">
                                                    Please enter Number.
                                                </div>
                                            </div>
                                        </div>
                                        </br>
                                        <div class="mb-6 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">capacity <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="capacity"
                                                    id="validationCustom02" placeholder="capacity" required="">
                                                    <input type="hidden" name="Restaurant_id" value="{{ $Restaurant_id }}">
                                                    <input type="hidden" name="seating_configuration" value="-">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="mb-4 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">location <span
                                                    class="text-danger">*</span>
                                            </label>
 

                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" name="location"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    <option selected value="Indoor">Indoor</option>
                                                    <option value="Outdoor">Outdoor</option>
                                                    <option value="Patio">Patio</option>
                                                    <option value="Rooftop">Rooftop</option>
                                                    <option value="Window_Side">Window Side</option>
                                                    <option selected value="Main Dining">Main Dining</option>
                                                    <option value="Private Room">Private Room</option>
                                                    <option value="Lounge">Lounge</option>
                                                    <option value="Terrace">Terrace</option>
                                                    <option value="Garden">Garden</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="mb-4 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">type <span
                                                    class="text-danger">*</span>
                                            </label>
                                            
                                            



                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" name="type"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    <option selected value="Standard">Standard</option>
                                                    <option value="Booth">Booth</option>
                                                    <option value="Bar">Bar</option>
                                                    <option value="Bar Lounge">Bar Lounge</option>
                                                    <option value="High Top">High Top</option>
                                                    <option value="Low Top">Low Top</option>
                                                    <option value="Chef’s Table">Chef’s Table</option>
                                                    <option value="Communal">Communal</option>
                                                    <option value="Counter">Counter</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please enter type.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">size
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" name="size"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    <option selected value="Single">Single</option>
                                                    <option value="Family">Family</option>
                                                    <option value="Group">Group</option>
                                                    <option value="Couple">Couple</option>
                                                    <option value="Large Party">Large Party</option>
                                                    <option value="Banquet">Banquet</option>
                                                    <option value="Event">Event</option>
                                                    <option value="Party">Party</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please enter capacity.
                                                </div>
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

                                                <button type="submit" name="action" style="width:250px;" value="more_add"
                                                    class="btn btn-primary">submit only</button>
                                            </div>
                                        </div>

                                        <div class="mb-6 row sub">
                                            <div class="col-lg-8 ms-auto">
                                                <button type="submit" name="action" style="width:250px;" value="add_and_cancel"
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
