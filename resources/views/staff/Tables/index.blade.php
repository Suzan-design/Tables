@extends('layouts.master')
@section('css')
@endsection
@section('title')
Restaurants Management - TABLES
@stop
@section('content')
<div class="container-fluid">
    <div class="col-xl-12 col-xxl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">

                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-block d-sm-flex border-0 align-items-center">
                                <div class="me-3">
                                    <button type="button" class="btn btn-rounded btn-outline-danger">
                                        <h4 class="card-title mb-2">Date : {{ $today }}</h4>
                                    </button>
                                </div>
                                <div class="btn-group" role="group" aria-label="Table actions">
                                    <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#add-category"
                                        class="btn btn-info btn-rounded btn-md">Select a day</a>
                                    <a href="{{ route('tables.create') }}" class="btn btn-primary btn-rounded btn-md">+
                                        Add New Table</a>
                                </div>
                            </div>
                            @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ session()->get('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">


                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @php
                            $i = 0;
                            @endphp
                            @foreach ($tables as $table)
                            @php
                            $i++;
                            @endphp
                            <div class="card-body tab-content p-0">
                                <div class="tab-pane fade active show" id="monthly" role="tabpanel">
                                    <div class="accordion style-1" id="accordion-one-{{ $table->id }}">
                                        <div class="accordion-item">
                                            <div class="accordion-header collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#default_collapseOne1-{{ $table->id }}"
                                                aria-expanded="false">

                                                <div class="me-3">
                                                    <p class="fs-14 mb-1">Table Number</p>

                                                    <span class="badge badge-danger">{{ $table->number }}</span>
                                                </div>
                                                <div class="me-3 pb-3">
                                                    <p class="fs-14 mb-1">Location</p>
                                                    <span class="badge badge-dark light">{{ $table->location }}</span>
                                                </div>
                                                <div class="me-3 pb-3">
                                                    <p class="fs-14 mb-1">Type</p>
                                                    <span class="badge badge-dark light">{{ $table->type }}</span>
                                                </div>
                                                <div class="me-3 pb-3">
                                                    <p class="fs-14 mb-1">Size</p>
                                                    <span class="badge badge-dark light">{{ $table->size }}</span>
                                                </div>
                                                <a class="btn btn-danger light" href="javascript:void(0);"
                                                    onclick="toggleAccordion({{ $table->id }})">{{$table->next_reservations_count}}</a>
                                                <span class="accordion-header-indicator"></span>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('tables.edit', $table->id) }}"
                                                            class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                                class="fas fa-pencil-alt"></i></a>

                                                        <a class="btn btn-danger shadow btn-xs sharp" href="#"
                                                            onclick="event.preventDefault();  document.getElementById('destroy-form-{{ $table->id }}').submit();">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        <form id="destroy-form-{{ $table->id }}"
                                                            action="{{ route('tables.destroy', $table->id) }}"
                                                            method="POST" style="display: none;">
                                                            @method('DELETE')
                                                            @csrf
                                                        </form>


                                                    </div>
                                                </td>
                                            </div>



                                            @foreach ($table->reservations as $reservation)
                                            <div id="default_collapseOne1-{{ $table->id }}"
                                                class="collapse accordion_body" data-bs-parent="#accordion-one">
                                                <div class="payment-details accordion-body-text">
                                                    <div class="me-3 mb-3">
                                                        <p class="fs-12 mb-2">Time</p>
                                                        <span class="font-w500">{{ $reservation->reservation_time
                                                            }}</span>
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
                                                        @if($reservation->status == 'next')

                                                            {{ $reservation->user->firstname ?? $reservation->first_name }}
                                                            
                                                            @else
                                                            'Not Available'
                                                            
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="me-3 mb-3">
                                                        <p class="fs-12 mb-2">Custumer Phone</p>
                                                        <span class="font-w500">

                                                            {{ $reservation->user->phone ?? 'Not Available' }}
                                                        </span>
                                                    </div>
                                                    <div class="me-3 mb-3">
                                                        <p class="fs-12 mb-2">party size</p>
                                                        <span class="font-w500">{{ $reservation->party_size ?? 'Not
                                                            Available' }}</span>
                                                    </div>


                                                    {{-- @if ($reservation->status == 'next' || $reservation->status ==
                                                    'scheduled')
                                                    <div class="me-3 mb-3">
                                                        <a href="{{route('reservations_start',$reservation->id)}}"
                                                            class="btn btn-primary btn-xxs shadow">Start</a>
                                                    </div>
                                                    @endif
                                                    @if ($reservation->status == 'current')
                                                    <div class="me-3 mb-3">
                                                        <a href="{{route('reservations_end',$reservation->id)}}"
                                                            class="btn btn-danger btn-xxs shadow">End</a>
                                                    </div>
                                                    @endif --}}
                                                    <!-- Button triggering modal -->
                                                    @if ($reservation->status != 'next')
                                                    <div class="me-1 mb-1">
                                                        <button id="startButton_{{ $reservation->id }}"
                                                            class="btn btn-primary btn-xxs shadow"
                                                            data-bs-toggle="modal" data-bs-target="#customerInfoModal"
                                                            onclick="setReservationId({{ $reservation->id }})">Start</button>
                                                    </div>
                                                    @endif

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="customerInfoModal" tabindex="-1"
                                                        aria-labelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalLabel">
                                                                        Customer
                                                                        Information</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="customerInfoForm">
                                                                        <input type="hidden" id="reservationId"
                                                                            name="reservation_id">
                                                                        <div class="mb-3">
                                                                            <label for="firstName"
                                                                                class="form-label">First
                                                                                Name</label>
                                                                            <input type="text" class="form-control"
                                                                                id="firstName" name="first_name"
                                                                                required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="lastName"
                                                                                class="form-label">Last
                                                                                Name</label>
                                                                            <input type="text" class="form-control"
                                                                                id="lastName" name="last_name" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="phoneNumber"
                                                                                class="form-label">Phone
                                                                                Number</label>
                                                                            <input type="tel" class="form-control"
                                                                                id="phoneNumber" name="phone_number"
                                                                                required>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" form="customerInfoForm"
                                                                        class="btn btn-primary">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    @if ($reservation->status == 'current')
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
                                                        <svg class="me-3" width="24" height="24" viewbox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade none-border" id="add-category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Select a day</strong></h4>
            </div>
            <div class="modal-body">
                <form method="get" action="{{ route('restaurant_tables') }}" autocomplete="off"
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
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</div>
<script>
    document.getElementById('addNewTableLink').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent the default link behavior
        document.getElementById('addNewTableForm').submit(); // Submit the form
    });
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
@endsection
@section('js')
@endsection