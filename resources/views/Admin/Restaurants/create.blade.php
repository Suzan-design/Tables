@extends('layouts.master')
@section('css')

@endsection
@section('title')
    Restaurants Management - Restaurant Add
@stop
@section('content')
    <link href="{{ URL::asset('dashboard/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
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
                        <h4 class="card-title">Restaurant Add</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation">
                            <form method="post" action="{{ route('Restaurants.store') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                 <div
                                 class="alert alert-danger">
                                 <ul>
                                 @foreach ($errors->all() as $error)
                                 <li>{{ $error }}
                                 </li>
                                 @endforeach
                                 </ul>
                                 </div>
                                 @endif
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Username
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control"
                                                    placeholder="Enter a username.."name="user_name" required="">
                                                @error('user_name')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Email <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="email"
                                                    id="validationCustom02" value="" placeholder="" required="">
                                                @error('email')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Email.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Password
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="password" name="password" class="form-control"
                                                    id="validationCustom03" placeholder="" required="">
                                                @error('password')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a password.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Phone (SY)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom08"
                                                    placeholder="phone number" value=""name="user_phone" required="">
                                                @error('user_phone')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a user_phone no.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">State
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" name="state"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    <option selected value="Damascus">Damascus</option>
                                                    <option value="Homs">Homs</option>
                                                    <option value="Lattakia">Lattakia</option>
                                                    <option value="Aleppo">Aleppo</option>
                                                    <option value="Tartus">Tartus</option>
                                                    <option value="As-suwayda">As-suwayda</option>
                                                </select>

                                                @error('state')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a state.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Deposit
                                                value
                                                <span class="text-danger">*</span>
                                                <input type="number"style="width:50%;" value=""
                                                    name="deposite_value"class="form-control" id="validationCustom07"
                                                    placeholder="" required="">
                                                @error('deposite_value')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </label>
                                            <div class="col-lg-6">
                                            <label class="col-form-label" for="validationCustom01">Deposite inforamtion En
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" col="1" class="form-control" id="validationCustom08" placeholder="Enter english desc" value="" name="deposit_desc" required="">
                                                </textarea>
                                                <label class="col-form-label" for="validationCustom01">Deposite inforamtion Ar
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" col="1" class="form-control" id="validationCustom08" placeholder="Enter arabic desc" value="" name="ar_Deposite_desc" required="">
                                                </textarea>
                                                @error('deposit_desc')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">refund policy
                                                <span class="text-danger">*</span>
                                                <input type="number"style="width:50%;" value=""
                                                    name="refund_value"class="form-control" id="validationCustom07"
                                                    placeholder="" required="">
                                                @error('refund_value')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </label>
                                            <div class="col-lg-6">
                                            <label class="col-form-label" for="validationCustom01">Refund inforamtion En
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder="refund_policy"
                                                    value=""name="refund_desc" required="">
                                            </textarea>
                                            <label class="col-form-label" for="validationCustom01">Refund inforamtion Ar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder="refund_policy"
                                                    value=""name="ar_refund_desc" required="">
                                            </textarea>
                                                @error('refund_desc')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a refund_policy.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label"
                                                for="validationCustom08">cancellition_policy
                                                <span class="text-danger">*</span>
                                                <input name="cancellition_value"class="form-control" style="width:50%;"
                                                    type="number" required>
                                                @error('cancellition_value')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </label>
                                            <div class="col-lg-6">
                                            <label class="col-form-label" for="validationCustom01">Cancel inforamtion En
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder="cancellition_policy"
                                                    value=""name="cancellition_desc" required="">
                                            </textarea>
                                            <label class="col-form-label" for="validationCustom01">Cancel inforamtion Ar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder="cancellition_policy"
                                                    value=""name="ar_cancellition_desc" required="">
                                            </textarea>
                                                @error('cancellition_desc')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        </div>
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom06">Restaurant
                                                Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" name="Restaurant_name" class="form-control"
                                                    id="validationCustom06" value=""placeholder="" required>
                                                <div class="invalid-feedback">
                                                    @error('Restaurant_name')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                    Please enter a name.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Phone (SY)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom08"
                                                    placeholder="" value="" name="Restaurant_phone"
                                                    required="">
                                                @error('Restaurant_phone')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Restaurant_phone no.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05"> Cuisine
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" name="cuisine_id"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    @foreach ($cuisins as $cuisin)
                                                        <option value="{{ $cuisin->id }}">{{ $cuisin->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('cuisine_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom05"> Category
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="default-select wide form-control" name="category_id"
                                                    id="validationCustom05" required>
                                                    <option data-display="Select">Please select</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please select a one.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Description
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" value=""
                                                    name="description" class="form-control" id="validationCustom07"
                                                    placeholder="" required="">
                                                <input type="text" value=""
                                                    name="ar_description" class="form-control" id="validationCustom07"
                                                    placeholder="" required="">
                                                @error('description')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a description.
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Facebook
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" value="" name="website"class="form-control"
                                                    id="validationCustom07" placeholder="" required="">
                                                @error('website')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Facebook link.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom07">Instagram
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" value="" name="instagram"class="form-control"
                                                    id="validationCustom07" placeholder="" required="">
                                                @error('instagram')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Insta.
                                                </div>
                                            </div>
                                        </div>


                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label"
                                                for="validationCustom09">Activation start <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control"name="Activation_start"
                                                    id="validationCustom09" placeholder="Activation_start"
                                                    required="">
                                                @error('Activation_start')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Activation_start.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom09">Activation end
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control"name="Activation_end"
                                                    id="validationCustom09" placeholder="Activation_end" required="">
                                                @error('Activation_end')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Activation end.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">The
                                                Appropriate Age Range For The Restaurant
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-4">
                                                <input type="number"style="width:50%;" min="15" max="80"
                                                    step="1" id="input-number" name="from"class="form-control"
                                                    id="validationCustom07" required="">
                                                @error('from')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="number"style="width:50%;" min="15" max="80"
                                                    step="1" id="input-number" name="to"class="form-control"
                                                    id="validationCustom07" required="">
                                                @error('to')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a refund_policy.
                                                </div>
                                            </div>
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">App Commission
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-4">
                                                <input type="number"style="width:100%;"
                                                    step="1" id="input-number" name="taxes" class="form-control"
                                                    id="validationCustom07" required="">
                                                @error('from')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        

                                    </div>

                                    <div class="col-xl-3">
                                        <div class="mb-3 row">

                                            <div class="col-lg-12">
                                                <input type="text" class="form-control"
                                                    placeholder="location" name="location" required="">
                                                @error('location')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a location.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="mb-3 row">

                                            <div class="col-lg-12">
                                                <input type="text" class="form-control"
                                                    placeholder="location ar" name="ar_location" required="">
                                                @error('ar_location')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a location.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="mb-3 row">

                                            <div class="col-lg-12">
                                                <input type="text" class="form-control" id="latitude"
                                                    placeholder="latitude"name="latitude" required=""
                                                    readonly>
                                                @error('latitude')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a latitude.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="mb-3 row">

                                            <div class="col-lg-12">
                                                <input type="text" name="longitude" id="longitude"
                                                    class="form-control" placeholder="longitude"
                                                    required="" readonly>
                                                @error('longitude')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror

                                                <div class="invalid-feedback">
                                                    Please enter a longitude.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-sm-6 col-12">
                                        <div id="map" style="height: 400px;     margin-bottom: 15px;"></div>
                                    </div>
                                    <div class="col-xl-4">
                                        <label class="col-lg-4 col-form-label" for="validationCustom09">Images(craousal)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-12">
                                            <input type="file" name="craousal[]" accept="images/*"
                                                class="form-file-input form-control" multiple required>
                                            @error('craousal')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <label class="col-lg-4 col-form-label" for="validationCustom09">Images(cover)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-12">
                                            <input type="file" name="cover" accept="images/*"
                                                class="form-file-input form-control" multiple required>
                                            @error('cover')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                        <label class="col-lg-4 col-form-label" for="validationCustom09">Images(gallery)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-12">
                                            <input type="file" name="gallery[]" accept="images/*"
                                                class="form-file-input form-control" multiple required>
                                            @error('gallery')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                        <label class="col-lg-4 col-form-label" for="validationCustom09">Images(logo)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-12">
                                            <input type="file" name="logo" accept="images/*"
                                                class="form-file-input form-control" multiple required>
                                            @error('logo')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                        </div>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header d-block">
                                                <h4 class="card-title">Services</h4>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($icons as $icon)
                                                    <div class="btn-group mb-1">
                                                        <div class="form-check custom-checkbox">
                                                            <input name="services[]" type="checkbox"
                                                                class="form-check-input" value="{{ $icon->id }}"
                                                                id="checkAll">
                                                            <label class="form-check-label" for="checkAll"></label>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-primary"style="height: 45px;">
                                                        
                                                        <span class="btn-icon-start">

                                                            <img style="height:16px;width:16px;"src="{{ asset($icon->image) }}"
                                                                alt="{{ $icon->name }}">
                                                        </span>
                                                         <span>
                                                        {{ $icon->name }}
                                                        </span>
                                                    </button>
                                                @endforeach
                                                @error('services')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="background-color: #fff3cd; border: 1px solid #ffeeba; border-radius::5px; padding: 10px;">
                                                <label class="form-label"><strong>?? Restaurant Type (Important)</strong></label>
                                                <div class="col">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="isFeatured" name="isFeatured">
                                                        <label class="custom-control-label" for="isFeatured">Toggle for Featured Restaurant</label>
                                                    </div>
                                                </div>
                                            </div>

                                </div>
                                <style>
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQqGaYBImwBfEwNfZEDkHDbOaJW7Pofrs&callback=initMap" async
        defer></script>
    <script>
      function initMap() {
          var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 10,
              center: {
                  lat: 33.5138,
                  lng: 36.2765
              }
          });
      
          var marker; // Declare a variable to hold the marker.
      
          map.addListener('click', function(e) {
              // Set the latitude and longitude values to input fields
              document.getElementById('latitude').value = e.latLng.lat();
              document.getElementById('longitude').value = e.latLng.lng();
      
              // Check if the marker already exists
              if (marker) {
                  // Move the existing marker to the new location
                  marker.setPosition(e.latLng);
              } else {
                  // Create a new marker at the clicked location
                  marker = new google.maps.Marker({
                      position: e.latLng,
                      map: map
                  });
              }
          });
      }
      </script>
    <script src="{{ URL::asset('dashboard/js/plugins-init/nouislider-init.js') }} "></script>

@endsection
@section('js')

@endsection
