@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/calender.css') }}" rel="stylesheet">
@endsection
@section('title')
    Restaurants Management - Promocode Create
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
                <li class="breadcrumb-item"><a href="javascript:void(0)">Promocode</a></li>
            </ol>
        </div>
        <!-- row -->
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Promocode Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation">
                            <form id="filter-form" method="post" action="{{ route('promocodes.store') }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Code
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"
                                                    placeholder="Enter a Code.." name="code" required="">
                                                @error('code')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a code.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Discount <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="discount"
                                                    id="validationCustom02" placeholder="Your valid discount.."
                                                    required="">
                                                @error('discount')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a discount.
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Limit <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="limit"
                                                    id="validationCustom02" placeholder="Your valid discount.."
                                                    required="">
                                                @error('limit')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a limit.
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03"> Start Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" name="start_date" class="form-control"
                                                    id="validationCustom03" placeholder="Choose a safe one.."
                                                    required="">
                                                @error('start_date')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a start_date.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">End Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" id="validationCustom08"
                                                    placeholder="963-9376-07234" name="end_date" required="">
                                                @error('end_date')
                                                     <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a end_date.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Description
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom08"
                                                   name="description" required="">
                                                @error('description')
                                                     <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter Description.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Image
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="file" class="form-control" id="validationCustom08"
                                                    name="image" required>
                                                @error('image')
                                                     <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please upload a image.
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-6">
                                        <div class="container">
                                            <h5>Filter Restaurant - <span id="num-results">0</span>
                                            </h5>
                                            <div class="row">
                                                <div class="col">
                                                    <label>City</label>
                                                    <select name="cities_res[]" id="field1" multiple
                                                        multiselect-search="true" multiselect-select-all="true"
                                                        multiselect-max-items="3"
                                                        onchange="console.log(this.selectedOptions)">
                                                        <option value="Aleppo">Aleppo</option>
                                                        <option value="Al-hasakah">Al-Ḥasakah</option>
                                                        <option value="Al-Qamishli">Al-Qamishli</option>
                                                        <option value="Quneitra">Al-Qunayṭirah</option>
                                                        <option value="Raqqa">Raqqa</option>
                                                        <option value="As-suwayda">Al-Suwayda</option>
                                                        <option value="Damascus">Damascus</option>
                                                        <option value="Daraa">Daraa</option>
                                                        <option value="Deir ez-zor">Deir ez-zor</option>
                                                        <option value="Ḥama">Ḥama</option>
                                                        <option value="Homs">Homs</option>
                                                        <option value="Idlib">Idlib</option>
                                                        <option value="Latakia">Latakia</option>
                                                        <option value="Rif Dimashq">Rif Dimashq</option>
                                                        <option value="Tartus">Tartus</option>
                                                    </select>
                                                    <br />
                                                    <label>Cuisine</label>
                                                    <select name="cuisines[]" id="field2" multiple
                                                        multiselect-search="true" multiselect-select-all="true"
                                                        multiselect-max-items="3"
                                                        onchange="console.log(this.selectedOptions)">
                                                        @foreach ($cuisines as $cuisine)
                                                            <option>{{ $cuisine->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <hr />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container">
                                            <h5>Filter User - <span id="num-users">0</span></h5>
                                            <div class="row">
                                                <div class="col">
                                                    <label>City</label>
                                                    <select name="cities_us[]" id="field2" multiple
                                                        multiselect-search="true" multiselect-select-all="true"
                                                        multiselect-max-items="3"
                                                        onchange="console.log(this.selectedOptions)">
                                                        <option value="Aleppo">Aleppo</option>
                                                        <option value="Al-hasakah">Al-Ḥasakah</option>
                                                        <option value="Al-Qamishli">Al-Qamishli</option>
                                                        <option value="Quneitra">Al-Qunayṭirah</option>
                                                        <option value="Raqqa">Raqqa</option>
                                                        <option value="As-suwayda">Al-Suwayda</option>
                                                        <option value="Damascus">Damascus</option>
                                                        <option value="Daraa">Daraa</option>
                                                        <option value="Deir ez-zor">Deir ez-zor</option>
                                                        <option value="Ḥama">Ḥama</option>
                                                        <option value="Homs">Homs</option>
                                                        <option value="Idlib">Idlib</option>
                                                        <option value="Latakia">Latakia</option>
                                                        <option value="Rif Dimashq">Rif Dimashq</option>
                                                        <option value="Tartus">Tartus</option>
                                                    </select>
                                                    <br />
                                                    <div class="btn-group mb-1">
                                                        <div class="form-check custom-checkbox">
                                                            <input name="is_followings" type="checkbox"
                                                                class="form-check-input" id="checkAll">
                                                            <label class="form-check-label" for="checkAll"></label>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-primary"style="height: 45px;
                                                                                                                                                                            width: 147px;
                                                                                                            padding: 5px;">follwings
                                                    </button>
                                                    <div class="btn-group mb-1">
                                                        <div class="form-check custom-checkbox">
                                                            <input name="is_reservaions" type="checkbox"
                                                                class="form-check-input"id="checkAll">
                                                            <label class="form-check-label" for="checkAll"></label>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-primary"style="height: 45px;                                                                                                                                               padding: 5px;">
                                                        num_reservations
                                                    </button>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="mb-3 col-md-4">
                                                            <label class="form-label">Num.rest</label>
                                                            <input type="number"
                                                                name="num_restaurants"class="form-control">
                                                        </div>
                                                        <div class="mb-3 col-md-4">
                                                            <label class="form-label">Num.users</label>
                                                            <input type="number"name="num_users" class="form-control">
                                                        </div>
                                                        <div class="mb-3 col-md-4">
                                                            <label class="form-label">Num.reservations</label>
                                                            <input type="number" name="num_reservations"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .content-body .container {
                                        margin-top: -6px;
                                    }

                                    .icons {
                                        display: flex;
                                        flex-wrap: wrap;
                                    }

                                    .icon {
                                        margin: 10px;
                                        text-align: center;
                                    }
                                </style>
                                <div class="col-xl-12">
                                    <style>
                                        .sub {
                                            display: inline-block;
                                        }
                                    </style>
                                    <div class="mb-6 row sub " style="color: black">
                                        <div class="col-lg-8 ms-auto">
                                            <button type="submit" name="action" style="width:250px;" value="more_add"
                                                class="btn btn-primary">submit And add more</button>
                                        </div>
                                    </div>
                                    <div class="mb-6 row sub">
                                        <div class="col-lg-8 ms-auto">
                                            <button type="submit" name="action" style="width:250px;" value="add_and_cancel"
                                                class="btn btn-danger">submit And Cancel</button>
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


    <script>
        $(function() {
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 500,
                values: [75, 300],
                slide: function(event, ui) {
                    $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                }
            });
            $("#amount").val("$" + $("#slider-range").slider("values", 0) +
                " - $" + $("#slider-range").slider("values", 1));
        });
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ URL::asset('dashboard/js/plugins-init/nouislider-init.js') }} "></script>
    <script>
        $('#filter-form select, #filter-form input').change(function() {
            var formData = $('#filter-form').serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('filter.restaurants.and.users') }}',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#num-results').text(data.restaurants);
                    $('#num-users').text(data.users);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection
@section('js')
@endsection
