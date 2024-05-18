@extends('layouts.master')
@section('css')
<!-- Custom CSS for scaled image previews -->
<style>
    .image-thumbnail {
        display: inline-block;
        margin: 10px;
        border: 1px solid #ddd;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        padding: 5px;
        border-radius: 5px;
        width: 150px; /* Width set to 150px */
        height: 150px; /* Height set to 150px */
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }
    .image-thumbnail img {
        max-width: 100%; /* Maximum width to fit parent */
        max-height: 100%; /* Maximum height to fit parent */
        height: auto; /* Auto height for natural aspect ratio */
        width: auto; /* Auto width for natural aspect ratio */
    }
    .delete-image {
        position: absolute;
        top: 5px;
        right: 5px;
        cursor: pointer;
        color: red;
        background-color: white;
        border-radius: 50%;
        padding: 2px;
    }
</style>
@endsection
@section('title')
    Restaurants Management - Restaurant Edit
@stop
@section('content')
    <link href="{{ URL::asset('dashboard/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Restaurants</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)"></a>Edit Restaurant</li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Restaurant Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation">
                            <form method="post" action="{{ route('Restaurants.update', $restaurant->id) }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Username
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom01"
                                                    placeholder="Enter a username.." value="{{ $restaurant->staff->name }}"
                                                    name="user_name">
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
                                                    id="validationCustom02" value="{{ $restaurant->staff->email }}"
                                                    placeholder="">
                                                @error('email')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Email.
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Password
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="password" name="password" class="form-control"
                                                    id="validationCustom03" placeholder="" required>
                                                @error('password')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a password.
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Phone (SY)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="validationCustom08"
                                                    placeholder="" value="{{ $restaurant->staff->phone }}"name="user_phone">
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
                                                    id="validationCustom05" >
                                                    <option data-display="Select">Please select</option>
                                                    <option value="Damascus"
                                                        {{ $restaurant->location->state == 'Damascus' ? 'selected' : '' }}>
                                                        Damascus</option>
                                                    <option value="Homs"
                                                        {{ $restaurant->location->state == 'Homs' ? 'selected' : '' }}>
                                                        Homs</option>
                                                    <option value="Lattakia"
                                                        {{ $restaurant->location->state == 'Lattakia' ? 'selected' : '' }}>
                                                        Lattakia</option>
                                                    <option value="Aleppo"
                                                        {{ $restaurant->location->state == 'Aleppo' ? 'selected' : '' }}>
                                                        Aleppo</option>
                                                    <option value="Tartus"
                                                        {{ $restaurant->location->state == 'Tartus' ? 'selected' : '' }}>
                                                        Tartus</option>
                                                    <option value="As-suwayda"
                                                        {{ $restaurant->location->state == 'As-suwayda' ? 'selected' : '' }}>
                                                        As-suwayda</option>
                                                </select>
                                                @error('state')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a state.
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $Deposite_information = explode(',', $restaurant->Deposite_information ?? '');
                                            $depositDesc = trim($Deposite_information[1] ?? '', '()');
                                            $depositValue = trim($Deposite_information[0] ?? '', '()');

                                            $refund_policy = explode(',', $restaurant->refund_policy ?? '');
                                            $refundDesc = trim($refund_policy[1] ?? '', '()');
                                            $refundValue = trim($refund_policy[0] ?? '', '()');
                                            $ar_refund_policy = explode(',', $restaurant->ar_refund_policy ?? '');
                                            $ar_refundDesc = trim($ar_refund_policy[1] ?? '', '()');
                                            
                                           // $change_policy = explode(',', $restaurant->change_policy ?? '');
                                           // $policyDesc = trim($change_policy[1] ?? '', '()');
                                           // $policyValue = trim($change_policy[0] ?? '', '()');
                                           // $ar_change_policy = explode(',', $restaurant->ar_change_policy ?? '');
                                            //$ar_policyDesc = trim($ar_change_policy[1] ?? '', '()');

                                            $cancellition_policy = explode(',', $restaurant->cancellition_policy ?? '');
                                            $cancellitionDesc = trim($cancellition_policy[1] ?? '', '()');
                                            $cancellitionValue = trim($cancellition_policy[0] ?? '', '()');
                                            $ar_cancellition_policy = explode(',', $restaurant->ar_cancellition_policy ?? '');
                                            $ar_cancellitionDesc = trim($ar_cancellition_policy[1] ?? '', '()');
                                        @endphp
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">Deposit
                                                Value
                                                <span class="text-danger">*</span>
                                                <input type="number"style="width:50%;" value="{{ $restaurant->Deposite_value  }}"
                                                    name="Deposite_value"class="form-control" id="validationCustom07"
                                                    placeholder="">
                                                @error('Deposite_value')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </label>
                                            <div class="col-lg-6">
                                            <label class="col-form-label" for="validationCustom01">Deposite inforamtion En
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder=""
                                                    value=""name="Deposite_desc">{{ $restaurant->Deposite_desc  }}
                                            </textarea>
                                            <label class="col-form-label" for="validationCustom01">Deposite inforamtion Ar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder=""
                                                    value=""name="ar_Deposite_desc">{{ $restaurant->ar_Deposite_desc  }}
                                            </textarea>
                                                @error('deposit_desc')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
 
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">refund_policy
                                                <span class="text-danger">*</span>


                                                <input type="number"style="width:50%;" value="{{ $refundValue }}"
                                                    name="refund_value"class="form-control" id="validationCustom07"
                                                    placeholder="">
                                                @error('refund_value')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </label>
                                            <div class="col-lg-6">
                                            <label class="col-form-label" for="validationCustom01">Refund inforamtion En
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder="refund_policy"
                                                    value=""name="refund_desc">{{ $refundDesc }}
                                            </textarea>
                                            <label class="col-form-label" for="validationCustom01">Refund inforamtion Ar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea type="textarea"row="3" class="form-control" id="validationCustom08" placeholder="refund_policy"
                                                    value=""name="ar_refund_desc">{{ $ar_refundDesc }}
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
                                                <input name="cancellition_value"
                                                    value="{{ $cancellitionValue }}"class="form-control"
                                                    style="width:50%;" type="number">
                                                @error('cancellition_value')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </label>
                                            <div class="col-lg-6">
                                            <label class="col-form-label" for="validationCustom01">Cancel inforamtion En
                                                <span class="text-danger">*</span>
                                            </label>
                                                <textarea type="textarea"row="3" value="" class="form-control" id="validationCustom08"
                                                    placeholder="cancellition_policy" value=""name="cancellition_desc">{{ $cancellitionDesc }}
                                            </textarea>
                                            <label class="col-form-label" for="validationCustom01">Cancel inforamtion Ar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea type="textarea"row="3" value="" class="form-control" id="validationCustom08"
                                                    placeholder="ar_cancellition_policy" value=""name="ar_cancellition_desc">{{ $ar_cancellitionDesc }}
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
                                                <input type="text" name="Restaurant_name" value="{{$restaurant->name}}" class="form-control"
                                                    id="validationCustom06" value=""placeholder="">
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
                                                    placeholder="" value="{{$restaurant->phone_number}}" name="Restaurant_phone"
                                                    ="">
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
                                                    id="validationCustom05" >
                                                    <option data-display="Select">Please select</option>
                                                    @foreach ($cuisins as $cuisin)
                                                        <option value="{{ $cuisin->id }}" {{ (old('cuisine_id', $restaurant->cuisine_id) == $cuisin->id) ? 'selected' : '' }}>{{ $cuisin->name }}</option>
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
                                                    id="validationCustom05" >
                                                    <option data-display="Select">Please select</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ (old('category_id', $restaurant->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}
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


                                                <input type="text"
                                                    name="description"class="form-control" value="{{$restaurant->description}}" id="validationCustom07"
                                                    >
                                                <input type="text"
                                                    name="ar_description"class="form-control" value="{{$restaurant->ar_description}}" id="validationCustom07"
                                                    >
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
                                                <input type="text" value="{{$restaurant->website}}" name="website"class="form-control"
                                                    id="validationCustom07" placeholder="" ="">
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
                                                <input type="text"  value="{{$restaurant->instagram}}"  name="instagram"class="form-control"
                                                    id="validationCustom07" placeholder="" ="">
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
                                                for="validationCustom09">Activation_start <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control"name="Activation_start"
                                                    id="validationCustom09"  value="{{$restaurant->Activation_start}}"  placeholder="Activation_start"
                                                    ="">
                                                @error('Activation_start')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Activation_start.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom09">Activation_end
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control"name="Activation_end"
                                                    id="validationCustom09"
                                                    value="{{$restaurant->Activation_end}}"
                                                     placeholder="Activation_end" ="">
                                                @error('Activation_end')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter a Activation_end.
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                         $ageRange = unserialize($restaurant->age_range);
                                         @endphp
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="from">The Appropriate Age Range For The Restaurant
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-4">
                                                <input type="number" style="width:50%;" min="15" max="80"
                                                    step="1" id="from" name="from" class="form-control" value="{{ $ageRange[0] }}"
                                                    required>
                                                @error('from')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="number" style="width:50%;" min="15" max="80"
                                                    step="1" id="to" name="to" class="form-control" value="{{ $ageRange[1] }}"
                                                    required>
                                                @error('to')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                            
                                            <label class="col-lg-4 col-form-label" for="validationCustom08">App Commission
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-4">
                                                <input type="number"style="width:100%;" 
                                                    step="1" id="input-number" name="taxes" class="form-control" value="{{$restaurant->taxes}}" id="validationCustom07" required="">
                                                @error('from')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        

                                    </div>

                                    <div class="col-xl-3">
                                        <div class="mb-3 row">

                                            <div class="col-lg-12">
                                                <input type="text" class="form-control" id="validationCustom08"
                                                    placeholder="location details(text)"
                                                    value="{{ $restaurant->location->text }}"
                                                     name="location">
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
                                                    placeholder="location ar" value="{{ $restaurant->location->ar_text }}" name="ar_location" required="">
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
                                                    placeholder="The coordinates (latitude)"name="latitude"
                                                    value="{{ $restaurant->location->latitude }}" readonly>
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
                                                    class="form-control"
                                                    value="{{ $restaurant->location->longitude }}"placeholder="The coordinates (longitude)"
                                                    readonly>
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
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="craousal">Images (Carousel):</label>
                                            <div class="col-lg-8">
                                                <input type="file" name="craousal[]" accept="image/*" class="form-control" multiple>
                                                <!-- Display existing carousel images -->
                                                <div class="existing-images">
                                                    @foreach ($restaurant->images->where('type', 'craousal') as $image)
                                                        <div class="image-thumbnail">
                                                            <img src="{{ asset($image->filename) }}" alt="Existing image" style="width: 100px; height: auto;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error('craousal')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                    
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="cover">Image (Cover):</label>
                                            <div class="col-lg-8">
                                                <input type="file" name="cover" accept="image/*" class="form-control">
                                                <!-- Display existing cover image -->
                                                <div class="existing-images">
                                                    @if($restaurant->images->where('type', 'cover')->first())
                                                        <div class="image-thumbnail">
                                                            <img src="{{ asset($restaurant->images->where('type', 'cover')->first()->filename) }}" alt="Existing image" style="width: 100px; height: auto;">
                                                        </div>
                                                    @endif
                                                </div>
                                                @error('cover')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                    
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="gallery">Images (Gallery):</label>
                                            <div class="col-lg-8">
                                                <input type="file" name="gallery[]" accept="image/*" class="form-control" multiple>
                                                <!-- Display existing gallery images -->
                                                <div class="existing-images">
                                                    @foreach ($restaurant->images->where('type', 'gallery') as $image)
                                                        <div class="image-thumbnail">
                                                            <img src="{{ asset($image->filename) }}" alt="Existing image" style="width: 100px; height: auto;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error('gallery')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                    
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="logo">Image (Logo):</label>
                                            <div class="col-lg-8">
                                                <input type="file" name="logo" accept="image/*" class="form-control">
                                                <!-- Display existing logo image -->
                                                <div class="existing-images">
                                                    @if($restaurant->images->where('type', 'logo')->first())
                                                        <div class="image-thumbnail">
                                                            <img src="{{ asset($restaurant->images->where('type', 'logo')->first()->filename) }}" alt="Existing image" style="width: 100px; height: auto;">
                                                        </div>
                                                    @endif
                                                </div>
                                                @error('logo')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
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

                                            <button type="submit" name="action" value="Save"
                                                class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                    {{-- <div class="mb-6 row sub">
                                        <div class="col-lg-8 ms-auto">
                                            <button type="cancel" name="action" value="Cancel"
                                                class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div> --}}
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
<script>
function removeImage(imageId) {
    // Implement AJAX request to remove image from the database and filesystem
    fetch(`/delete-image/${imageId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`img[src="${data.imageUrl}"]`).parentNode.remove();
            } else {
                alert('Error removing image');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Optional: Add more JavaScript to handle resizing or other interactions
</script>
@endsection
