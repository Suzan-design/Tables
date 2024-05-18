@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management - Restaurant Details
@stop
@section('content')
    <div class="container-fluid">


        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Details</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Restaurants</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="profile card card-body px-3 pt-3 pb-0">
                    <div class="profile-head">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h4 class="card-intro-title mb-4">Restaurant Details</h4>
                                    <div class="bootstrap-carousel">
                                        <div id="carouselExampleIndicators2" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                @php $counter = 0; @endphp
                                                @foreach ($Restaurant->images as $image)
                                                    @if ($image->type === 'craousal')
                                                        <button type="button" data-bs-target="#carouselExampleIndicators"
                                                            data-bs-slide-to="{{ $counter }}"
                                                            class="{{ $counter === 0 ? 'active' : '' }}"
                                                            aria-label="Slide {{ $counter + 1 }}"></button>
                                                        @php $counter++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="carousel-inner">
                                                @php $counter = 0; @endphp
                                                @foreach ($Restaurant->images as $image)
                                                    @if ($image->type === 'craousal')
                                                        <div class="carousel-item {{ $counter === 0 ? 'active' : '' }}">
                                                            <img class="d-block w-100" src="{{ asset($image->filename) }}"
                                                                alt="Slide {{ $counter + 1 }}">
                                                        </div>
                                                        @php $counter++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carouselExampleIndicators2" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carouselExampleIndicators2" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-info">
                            <div class="media pt-3 pb-3">
                                @foreach ($Restaurant->images as $image)
                                    @if ($image->type === 'logo')
                                        <img src="{{ asset($image->filename) }}" alt="image" class="me-3 rounded"
                                            width="75">
                                    @endif
                                @endforeach

                                <div class="media-body">
                                    <h5 class="m-b-5">
                                        <a href="post-details.html" class="text-black">{{ $Restaurant->staff->name }}</a>
                                    </h5>
                                    <p class="mb-0">
                                        {{ $Restaurant->staff->email }}
                                    </p>
                                </div>
                            </div>
                            <div class="profile-details">
                                <div class="profile-name px-3 pt-2">
                                    <h4 class="text-primary mb-0"></h4>
                                    <p></p>
                                </div>
                                <div class="profile-email px-2 pt-2">
                                    <h4 class="text-muted mb-0"></h4>
                                    <p></p>
                                </div>
                                <div class="dropdown ms-auto">
                                    <div class="mt-4">
                                        <a href="{{ route('restaurant_reservations') }}"
                                            class="btn btn-primary mb-1">Reservations Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-tab">
                            <div class="custom-tab-1">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a href="#Restaurant" data-bs-toggle="tab"
                                            class="nav-link active show">Restaurant</a>
                                    </li>
                                    <li class="nav-item"><a href="#gallery" data-bs-toggle="tab"
                                            class="nav-link">Gallery</a>
                                    </li>
                                    <li class="nav-item"><a href="#location" data-bs-toggle="tab"
                                            class="nav-link">Location</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="Restaurant" class="tab-pane fade active show">
                                        <div class="profile-about-me">
                                            <div class="pt-4 border-bottom-1 pb-3">
                                                <h4 class="text-primary">About Me</h4>
                                                <p class="mb-2">{{ $Restaurant->description }}</p>
                                            </div>
                                        </div>
                                        <div class="profile-about-me">
                                            <div class="pt-4 border-bottom-1 pb-3">
                                                <h4 class="text-primary">{{ $Restaurant->cuisine->name }}</h4>
                                                <p class="mb-2">{{ $Restaurant->cuisine->desc }} </p>
                                            </div>
                                        </div>


                                        <div class="profile-skills mb-5">
                                            <h4 class="text-primary mb-2">Menu</h4>
                                            @forelse($Restaurant->menu as $men)
                                                <a href="javascript:void(0);" class="btn btn-primary light btn-xs mb-1">
                                                    {{ $men->name }} : {{ $men->price }}
                                                </a>
                                            @empty
                                            @endforelse

                                        </div>
                                        <div class="profile-personal-info">
                                            <h4 class="text-primary mb-4">Contact Information</h4>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">Name <span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7"><span>{{ $Restaurant->name }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">location <span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7">
                                                    <span>{{ $Restaurant->location->state }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">Activation_start <span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7">
                                                    <span>{{ $Restaurant->Activation_start }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">Activation_end <span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7"><span>{{ $Restaurant->Activation_end }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">phone_number <span class="pull-end">:</span></h5>
                                                </div>
                                                <div class="col-sm-9 col-7"><span>{{ $Restaurant->phone_number }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="gallery" class="tab-pane fade ">
                                        <div class="my-post-content pt-3">
                                            @foreach ($Restaurant->images as $image)
                                                <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                                    <img src="{{ asset($image->filename) }}" alt=""
                                                        class="img-fluid w-100 rounded" width="200px" height="200px">
                                                    <button class="btn btn-primary me-2"><span class="me-2"><i
                                                                class="fa fa-heart"></i></span>{{ $image->type }}</button>
                                                    {{-- <button class="btn btn-secondary" data-bs-toggle="modal"
                                                        data-bs-target="#replyModal"><span class="me-2"><i
                                                                class="fa fa-reply"></i></span>Edit</button> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div id="location" class="tab-pane fade">
                                        <div class="profile-personal-info">
                                            <div class="pt-4 border-bottom-1 pb-3">
                                                <h4 class="text-primary">Location</h4>

                                            </div>




                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">State <span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7">
                                                    <span>{{ $Restaurant->location->state }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">Text(details)<span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7"><span>{{ $Restaurant->location->text }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">Latitude <span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7">
                                                    <span>{{ $Restaurant->location->latitude }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-3 col-5">
                                                    <h5 class="f-w-500">Longitude<span class="pull-end">:</span>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-9 col-7">
                                                    <span>{{ $Restaurant->location->longitude }}</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-body">
                                            <div id="map" style="height: 450px;"></div>
                                        </div>




                                    </div>

                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="replyModal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Post Reply</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <textarea class="form-control" rows="4">Message</textarea>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger light"
                                                data-bs-dismiss="modal">btn-close</button>
                                            <button type="button" class="btn btn-primary">Reply</button>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQqGaYBImwBfEwNfZEDkHDbOaJW7Pofrs"></script>
    <script>
        function initMap() {
            var restaurantLocation = {
                lat: {{ $Restaurant->location->latitude }},
                lng: {{ $Restaurant->location->longitude }}
            };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: restaurantLocation
            });

            var marker = new google.maps.Marker({
                position: restaurantLocation,
                map: map
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQqGaYBImwBfEwNfZEDkHDbOaJW7Pofrs&callback=initMap"></script>





@endsection
@section('js')
@endsection
