@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/calender.css') }}" rel="stylesheet">
    <style>
        .accordion.style-1 .accordion_body .payment-details span {
            font-size: 1rem;
            padding: inherit;
        }
    </style>
@endsection
@section('title')
    Restaurants Management - Reservations
@stop
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-block d-sm-flex border-0">
                                <div class="me-3">
                                    <button type="button" class="btn btn-rounded btn-outline-danger">
                                        <h4 class="card-title mb-2">Date : {{ $date }}</h4>
                                    </button>
                                    {{-- <span class="fs-12"></span> --}}
                                </div>
                                <div class="card-tabs mt-3 mt-sm-0">
                                    <ul class="nav nav-tabs" role="tablist">

                                        <li class="nav-item">
                                            <a href="javascript:void()" data-bs-toggle="modal"
                                                data-bs-target="#add-category" class="btn btn-primary btn-event w-100">
                                                <span class="align-middle"></span>Select a day
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @foreach ($tables as $index => $table)
                                <div class="card-body tab-content p-0">
                                    <div class="tab-pane fade active show" id="monthly" role="tabpanel">
                                        <div id="accordion-one-{{ $index }}" class="accordion style-1">
                                            <div class="accordion-item">
                                                <div class="accordion-header collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#default_collapseOne1-{{ $index }}"
                                                    aria-expanded="false">
                                                    <div class="d-flex align-items-center">
                                                        <div class="profile-image">
                                                            <img src="{{ URL::asset('dashboard/images/avatar/R.jpg') }}"
                                                                alt="">

                                                        </div>
                                                        <div class="user-info">
                                                            <h6 class="fs-16 font-w700 mb-0"><a href="javascript:void(0)">
                                                                    Table ID</a></h6>
                                                            <span class="fs-14">{{ $table->number }}</span>
                                                        </div>
                                                    </div>
                                                    <span>{{ $date }}</span>
                                                    <span>capacity : {{ $table->capacity }} </span>
                                                    <a class="btn btn-danger light" href="javascript:void(0);"
                                                        onclick="toggleAccordion({{ $index }})">Details</a>
                                                    <span class="accordion-header-indicator"></span>
                                                </div>
                                                @foreach ($table->reservations as $reservation)
                                                    <div id="default_collapseOne1-{{ $index }}"
                                                        class="collapse accordion_body" data-bs-parent="#accordion-one">
                                                        <div class="payment-details accordion-body-text">
                                                            <div class="me-3 mb-3">
                                                                <p class="fs-12 mb-2">Time</p>
                                                                <span
                                                                    class="font-w500">{{ $reservation->reservation_time }}</span>
                                                            </div>
                                                            <div class="me-3 mb-3">
                                                                <p class="fs-12 mb-2">Status</p>
                                                                <span id="reservationStatus_{{ $reservation->id }}"
                                                                    class="font-w500">
                                                                    @if ($reservation->status == 'next')
                                                                        reserved
                                                                    @else
                                                                        Available
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="me-3 mb-3">
                                                                <p class="fs-12 mb-2">Custumer Name</p>
                                                                <span class="font-w500">
                                                                    @if($reservation->status =='next')
                                                                    {{ $reservation->user->name ?? $reservation->first_name }}
                                                                    @else
                                                                    Not Available
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="me-3 mb-3">
                                                                <p class="fs-12 mb-2">Custumer Phone</p>
                                                                <span class="font-w500">

                                                                    {{ $reservation->user->phone ?? 'Not Available' }}
                                                                </span>
                                                            </div>


                                                            {{-- @if ($reservation->status == 'next' || $reservation->status == 'scheduled')
                                                            <div class="me-3 mb-3">
                                                            <a href="{{route('reservations_start',$reservation->id)}}" class="btn btn-primary btn-xxs shadow">Start</a>
                                                            </div>
                                                            @endif
                                                            @if ($reservation->status == 'next')
                                                            <div class="me-3 mb-3">
                                                            <a href="{{route('reservations_end',$reservation->id)}}" class="btn btn-danger btn-xxs shadow">End</a>
                                                            </div>
                                                            @endif --}}
                                                            <!-- Button triggering modal -->
                                                            @if ($reservation->status != 'next')
                                                                <div class="me-1 mb-1">
                                                                    <button id="startButton_{{ $reservation->id }}"
                                                                        class="btn btn-primary btn-xxs shadow"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#customerInfoModal"
                                                                        onclick="setReservationId({{ $reservation->id }})">Start</button>
                                                                </div>
                                                            @endif

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="customerInfoModal" tabindex="-1"
                                                                aria-labelledby="modalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="modalLabel">Customer
                                                                                Information</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form method="post" action="{{ route('reservations_start_ajax') }}" id="customerInfoForm" style="color:black">
                                                                            @csrf
                                                                                <input type="hidden" id="reservationId"
                                                                                    name="reservation_id">
                                                                                <div class="mb-3">
                                                                                    <label for="firstName"
                                                                                        class="form-label">First
                                                                                        Name</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="firstName" name="first_name"
                                                                                        required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="lastName"
                                                                                        class="form-label">Last
                                                                                        Name</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="lastName" name="last_name"
                                                                                        required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="phoneNumber"
                                                                                        class="form-label">Phone
                                                                                        Number</label>
                                                                                    <input type="tel"
                                                                                        class="form-control"
                                                                                        id="phoneNumber"
                                                                                        name="phone_number" required>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" form="customerInfoForm"
                                                                                class="btn btn-primary">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                            @if ($reservation->status == 'next')
                                                                <div class="me-3 mb-3">
                                                                    <button id="endButton_{{ $reservation->id }}"
                                                                        onclick="endReservation({{ $reservation->id }})"
                                                                        class="btn btn-danger btn-xxs shadow">End</button>
                                                                </div>
                                                            @endif
                                                            <div class="me-3 mb-3">
                                                                <button id="endButton_{{ $reservation->id }}"
                                                                    onclick="endReservation({{ $reservation->id }})"
                                                                    class="btn btn-danger btn-xxs shadow"
                                                                    style="display:none">End</button>
                                                            </div>



                                                            <div class="me-3 mb-3">
                                                                <a href="{{ route('reservations_details', $reservation->id) }}"
                                                                    class="btn btn-warning btn-xxs shadow">Details</a>
                                                            </div>
                                                            <div class="info mb-3">
                                                                <svg class="me-3" width="24" height="24"
                                                                    viewbox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M12 1C9.82441 1 7.69767 1.64514 5.88873 2.85384C4.07979 4.06253 2.66989 5.7805 1.83733 7.79049C1.00477 9.80047 0.786929 12.0122 1.21137 14.146C1.6358 16.2798 2.68345 18.2398 4.22183 19.7782C5.76021 21.3166 7.72022 22.3642 9.85401 22.7887C11.9878 23.2131 14.1995 22.9953 16.2095 22.1627C18.2195 21.3301 19.9375 19.9202 21.1462 18.1113C22.3549 16.3023 23 14.1756 23 12C22.9966 9.08368 21.8365 6.28778 19.7744 4.22563C17.7122 2.16347 14.9163 1.00344 12 1ZM12 21C10.22 21 8.47992 20.4722 6.99987 19.4832C5.51983 18.4943 4.36628 17.0887 3.68509 15.4442C3.0039 13.7996 2.82567 11.99 3.17294 10.2442C3.5202 8.49836 4.37737 6.89471 5.63604 5.63604C6.89472 4.37737 8.49836 3.5202 10.2442 3.17293C11.99 2.82567 13.7996 3.0039 15.4442 3.68509C17.0887 4.36627 18.4943 5.51983 19.4832 6.99987C20.4722 8.47991 21 10.22 21 12C20.9971 14.3861 20.0479 16.6736 18.3608 18.3608C16.6736 20.048 14.3861 20.9971 12 21Z"
                                                                        fill="#fff"></path>
                                                                    <path
                                                                        d="M12 9C11.7348 9 11.4804 9.10536 11.2929 9.29289C11.1054 9.48043 11 9.73478 11 10V17C11 17.2652 11.1054 17.5196 11.2929 17.7071C11.4804 17.8946 11.7348 18 12 18C12.2652 18 12.5196 17.8946 12.7071 17.7071C12.8947 17.5196 13 17.2652 13 17V10C13 9.73478 12.8947 9.48043 12.7071 9.29289C12.5196 9.10536 12.2652 9 12 9Z"
                                                                        fill="#fff"></path>
                                                                    <path
                                                                        d="M12 8C12.5523 8 13 7.55228 13 7C13 6.44771 12.5523 6 12 6C11.4477 6 11 6.44771 11 7C11 7.55228 11.4477 8 12 8Z"
                                                                        fill="#fff"></path>
                                                                </svg>

                                                                <p class="mb-0 fs-14">
                                                                    comment:
                                                                    {{ $reservation->speacial_request ?? 'Not Available' }}

                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                </br>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Add Category -->
            <div class="modal fade none-border" id="add-category">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><strong>Select a day</strong></h4>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{ route('restaurant_reservations') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label form-label">Date</label>
                                        <input class="form-control form-white" type="date" name="date" required>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit"class="btn btn-danger">Confirm</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function startReservation(id) {
            $.ajax({
                url: "{{ route('reservations_start_ajax', ':id') }}".replace(':id', id),
                method: 'GET',
                success: function(response) {
                    $('#startButton_' + id).hide();
                    $('#endButton_' + id).show();
                    $('#reservationStatus_' + id).text('current');
                    document.getElementById("endButton_" + reservationId).style.display = "";

                    // إخفاء زري Edit و Delete
                    $('#editButton_' + id).hide();
                    $('#deleteButton_' + id).hide();
                },
                error: function(error) {
                    alert('An error occurred');
                    console.log(error);
                }
            });
        }
    </script>

    <script>
        function endReservation(id) {
            $.ajax({
                url: "{{ route('reservations_end_ajax', ':id') }}".replace(':id', id),
                method: 'GET',
                success: function(response) {
                    $('#endButton_' + id).css('display', 'none');
                    $('#reservationStatus_' + id).text('finite');
                },
                error: function(error) {
                    alert('An error occurred');
                    console.log(error);
                }
            });
        }
    </script>
    <script>
        function toggleAccordion(index) {
            const header = document.querySelector(`#accordion-one-${index} .accordion-header`);
            const body = document.querySelector(`#default_collapseOne1-${index}`);

            if (header.classList.contains('collapsed')) {
                header.classList.remove('collapsed');
                header.setAttribute('aria-expanded', 'true');
                body.classList.add('show');
            } else {
                header.classList.add('collapsed');
                header.setAttribute('aria-expanded', 'false');
                body.classList.remove('show');
            }
        }
    </script>
<script>
    function setReservationId(reservationId) {
    document.getElementById('reservationId').value = reservationId;
    }
    </script>

@endsection
@section('js')
@endsection
