@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/calender.css') ?? '-' }}" rel="stylesheet">
    <style>
        .w-32 {
            width: 8rem;
            float: left;
            margin: 5px;

        }
    </style>
@endsection
@section('title')
    Restaurants Management - Table Details
@stop
@section('content')

    <div class="container-fluid">

        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Reservations</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
            </ol>
        </div>
        <!-- row -->


        <div class="row">
            <div class="col-xl-3 col-xxl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-intro-title">Table Details</h4>

                        <div class="">
                            <div id="external-events" class="my-3">
                                <p></p>
                                <div class="external-event btn-primary light" data-class="bg-primary"><i
                                        class="fa fa-move"></i><span>Number : {{ $table->number ?? '-' }}</span></div>
                                <div class="external-event btn-info light" data-class="bg-info"><i
                                        class="fa fa-move"></i>Capacity : {{ $table->capacity ?? '-' }}</div>

                                <div class="external-event btn-danger light" data-class="bg-danger"><i
                                        class="fa fa-move"></i>Type : {{ $table->type ?? '-' }}
                                </div>
                                <div class="external-event btn-danger light" data-class="bg-danger"><i
                                        class="fa fa-move"></i>Location : {{ $table->location ?? '-' }}
                                </div>
                                <div class="external-event btn-danger light" data-class="bg-danger"><i
                                        class="fa fa-move"></i>Size : {{ $table->size ?? '-' }}
                                </div>
                            </div>
                            <!-- checkbox -->
                            <div class="checkbox form-check checkbox-event custom-checkbox pt-3 pb-5">
                                <label class="form-check-label" for="drop-remove">
                                    <span class="badge badge-lg light badge-danger"> DATE : {{ $today ?? '-' }}</span>
                                </label>
                                <input type="hidden" value="{{ $today ?? '-' }}" name="today">
                            </div>
                            <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#add-category"
                                class="btn btn-primary btn-event w-100">
                                <span class="align-middle"><i class="ti-date"></i></span> Select a day
                            </a>
                                <a style="margin-top:10px;"href="javascript:void()" data-bs-toggle="modal"
                                    data-bs-target="#add-reservation" class="btn btn-danger btn-event w-100">
                                    <span class="align-middle"><i class="ti-date"></i></span> Add reservation
                                </a>
                                <a style="margin-top:10px;"href="javascript:void()" data-bs-toggle="modal"
                                    data-bs-target="#reservation" class="btn btn-secondary btn-event w-100">
                                    <span class="align-middle"><i class="ti-date"></i></span> reservation
                                </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-xxl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="min-w-32 bg-white min-h-48 p-3 mb-4 font-medium">
                            @php
                                $i = 0;
                            @endphp
                            @if ($table && $table->reservations)
                                @forelse ($table->reservations as $reservation)
                                    @php
                                        $i++;
                                    @endphp
                                    <div
                                        class="w-32 flex-none rounded-t lg:rounded-t-none lg:rounded-l text-center shadow-lg ">
                                        <div class="block rounded-t overflow-hidden  text-center ">
                                            @if ($reservation->status == 'next' || $reservation->status == 'scheduled')
                                                <div id="reservationStatus_{{ $reservation->id }}"
                                                    style="background-color:#5bcfc5;" class="bg text-white py-1">
                                                @elseif ($reservation->status == 'current')
                                                    <div id="reservationStatus_{{ $reservation->id }}"
                                                        style="background-color:#f52b4f;" class="bg text-white py-1">
                                                    @else
                                                        <div id="reservationStatus_{{ $reservation->id }}"
                                                            style="background-color: #b38cd2" class="bg text-white py-1">
                                            @endif

                                            @if ($reservation->status == 'scheduled')
                                                available
                                            @elseif ($reservation->status == 'next')
                                                reserved
                                            @else
                                                {{ $reservation->status ?? '-' }}
                                            @endif

                                        </div>
                                        <div class="pt-1 border-l border-r border-white bg-white">
                                            <span class="text-5xl font-bold leading-tight">
                                                <a href="{{ route('reservations.show', $reservation->id) ?? '-' }}">
                                                    {{ $reservation->reservation_time ?? '-' }}
                                                </a>
                                            </span>
                                        </div>
                                        <div
                                            class="border-l border-r border-b rounded-b-lg text-center border-white bg-white -pt-2 -mb-1">
                                            <span class="text-sm">

                                                {{-- @if ($reservation->status == 'next' || $reservation->status == 'scheduled')
                                                    @can('edit_delete_reservation')
                                                        <a id="startButton_{{ $reservation->id }}"
                                                            onclick="SR({{ $reservation->id }})"
                                                            class="btn btn-warning btn-xxs shadow">Edit</a>
                                                        <a id="startButton_{{ $reservation->id }}"
                                                            onclick="SR({{ $reservation->id }})"
                                                            style="margin-top:10px;"class="btn btn-danger btn-xxs shadow">Delete</a>
                                                    @endcan
                                                @endif --}}

                                            </span>
                                        </div>
                                        <div
                                            class="pb-2 border-l border-r border-b rounded-b-lg text-center border-white bg-white">
                                            <span class="text-xs leading-normal">
                                            </span>
                                        </div>

                                    </div>
                        </div>
                    @empty
                        @endforelse
                        @endif


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
                        <form method="get" action="{{ route('tables.show', $table->id) }}" autocomplete="off"
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
        <div class="modal fade none-border" id="add-reservation">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add a Reservation</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('reservations.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label form-label">Time</label>
                                    <input class="form-control form-white" type="time" name="reservation_time"
                                        required>
                                    <input type="hidden" name="table_id" value={{ $table->id }}>
                                    <input type="hidden" name="reservation_date" value={{ $today }}>
                                    <input type="hidden" name="Restaurant_id" value={{ $table->Restaurant_id }}>
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
        <div class="modal fade none-border" id="reservation">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: rgb(88, 88, 88); color: whitesmoke">
                    <div class="modal-header">
                        <h4 class="modal-title" style="color: whitesmoke"><strong>Add a Reservation</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('generate_table_reservations') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div> <label class="text-grey">Duration</label>
                                    <input class="ml-2" style="color: gray" value="-" type="number" name="duration" required>
                                    @error('duration')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-danger"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="table_id" value={{ $table->id }}>
                                    <input type="hidden" name="reservation_date" value={{ $today }}>
                                    <input type="hidden" name="Restaurant_id" value={{ $table->Restaurant_id }}>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                    data-bs-dismiss="modal" class="btn btn-danger">Close</button>
                                <button type="submit"class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- <!-- BEGIN MODAL -->
        <div class="modal fade none-border" id="event-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add New Event</strong></h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success save-event waves-effect waves-light">Create
                            event</button>

                        <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-bs-toggle="modal">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Add Category -->
        <div class="modal fade none-border" id="add-category">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add a category</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label form-label">Category Name</label>
                                    <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name">
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label form-label">Choose Category Color</label>
                                    <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                        <option value="success">Success</option>
                                        <option value="danger">Danger</option>
                                        <option value="info">Info</option>
                                        <option value="pink">Pink</option>
                                        <option value="primary">Primary</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-bs-toggle="modal">Save</button>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function SR(id) {
            $.ajax({
                url: "{{ route('reservations_start_ajax', ':id') }}".replace(':id', id),
                method: 'GET',
                success: function(response) {
                    $('#startButton_' + id).hide();
                    $('#reservationStatus_' + id).text('current');
                    $('#endButton_' + id).show(); // إظهار زر الانتهاء بعد تحديث الحالة
                    document.getElementById("endButton_" + reservationId).style.display = "";

                    $('#reservationStatus_' + id).css('background-color', '#f72b50');
                },
                error: function(error) {
                    alert('An error occurred');
                    console.log(error);
                }
            });
        }
    </script>
    <script>
        function ER(id) {
            $.ajax({
                url: "{{ route('reservations_end_ajax', ':id') }}".replace(':id', id),
                method: 'GET',
                success: function(response) {
                    $('#endButton_' + id).css('display', 'none');
                    $('#reservationStatus_' + id).text('finite');

                    $('#reservationStatus_' + id).css('background-color', '#b08acf');
                },
                error: function(error) {
                    alert('An error occurred');
                    console.log(error);
                }
            });
        }
    </script>

@endsection
@section('js')

@endsection
