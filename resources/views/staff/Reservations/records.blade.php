@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management.Table Add
@stop
@section('content')
    <style>
        .accordion.style-1 .accordion_body .payment-details span {
            font-size: 1rem;
            padding: inherit;
        }
    </style>
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Records</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Working Times</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-block d-sm-flex border-0">
                        <div class="me-3">
                            <h4 class="card-title mb-2">Working Times</h4>
                        </div>
                        <div class="card-tabs mt-3 mt-sm-0">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a href="{{ route('reservations_generate_get', $Restaurant->id) }}"
                                                class="btn btn-primary d-sm-inline-block d-none  "
                                                alt="Transparent MDB Logo" id="animated-img1">
                                                Working times Management
                                                <i class="las la-signal ms-3 scale5"></i></a>
                                        </li>
                                    </ul>
                                </div>
                    </div>
                    @forelse($times as $time)
                        <div class="card-body tab-content p-0">
                            <div class="tab-pane fade active show" id="monthly" role="tabpanel">
                                <div id="accordion-one" class="accordion style-1">
                                    <div class="accordion-item">
                                        <div class="accordion-header collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#default_collapseOne1">
                                            <div class="d-flex align-items-center">
                                                <div class="profile-image">
                                                    <img src="{{ URL::asset('dashboard/images/avatar/R.jpg') }}"
                                                        alt="">
                                                    <span class="bg-success" style="background-color:#f72b50">
                                                        <svg width="16" height="16" viewbox="0 0 16 16"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip3)">
                                                                <path
                                                                    d="M10.4125 14.85C10.225 14.4625 10.3906 13.9937 10.7781 13.8062C11.8563 13.2875 12.7688 12.4812 13.4188 11.4719C14.0844 10.4375 14.4375 9.23749 14.4375 7.99999C14.4375 4.44999 11.55 1.56249 8 1.56249C4.45 1.56249 1.5625 4.44999 1.5625 7.99999C1.5625 9.23749 1.91562 10.4375 2.57812 11.475C3.225 12.4844 4.14062 13.2906 5.21875 13.8094C5.60625 13.9969 5.77187 14.4625 5.58437 14.8531C5.39687 15.2406 4.93125 15.4062 4.54062 15.2187C3.2 14.575 2.06562 13.575 1.2625 12.3187C0.4375 11.0312 -4.16897e-07 9.53749 -3.49691e-07 7.99999C-2.56258e-07 5.86249 0.83125 3.85312 2.34375 2.34374C3.85313 0.831242 5.8625 -7.37314e-06 8 -7.2797e-06C10.1375 -7.18627e-06 12.1469 0.831243 13.6563 2.34374C15.1688 3.85624 16 5.86249 16 7.99999C16 9.53749 15.5625 11.0312 14.7344 12.3187C13.9281 13.5719 12.7938 14.575 11.4563 15.2187C11.0656 15.4031 10.6 15.2406 10.4125 14.85Z"
                                                                    fill="white"></path>
                                                                <path
                                                                    d="M11.0407 8.41563C11.1938 8.56876 11.2688 8.76876 11.2688 8.96876C11.2688 9.16876 11.1938 9.36876 11.0407 9.52188L9.07503 11.4875C8.78753 11.775 8.40628 11.9313 8.00315 11.9313C7.60003 11.9313 7.21565 11.7719 6.93127 11.4875L4.96565 9.52188C4.6594 9.21563 4.6594 8.72188 4.96565 8.41563C5.2719 8.10938 5.76565 8.10938 6.0719 8.41563L7.22502 9.56876L7.22502 5.12814C7.22502 4.69689 7.57503 4.34689 8.00628 4.34689C8.43753 4.34689 8.78753 4.69689 8.78753 5.12814L8.78753 9.57188L9.94065 8.41876C10.2407 8.11251 10.7344 8.11251 11.0407 8.41563Z"
                                                                    fill="white"></path>
                                                            </g>
                                                            <defs>
                                                                <clippath id="clip3">
                                                                    <rect width="16" height="16" fill="white"
                                                                        transform="matrix(-4.37114e-08 1 1 4.37114e-08 0 -7.62939e-06)">
                                                                    </rect>
                                                                </clippath>
                                                            </defs>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="user-info">
                                                    <h6 class="fs-16 font-w700 mb-0"><a
                                                            href="javascript:void(0)">{{ $Restaurant->name }}</a></h6>
                                                    <span class="fs-14">{{ $Restaurant->phone_number }}</span>
                                                </div>
                                            </div>
                                            <span>Start Date : {{ $time->date_start }}</span>
                                            <span></span>
                                            <span>End Date : {{ $time->date_end }}</span>
                                            <a class="btn btn-danger light" href="javascript:void(0);">Times</a>
                                            <span class="accordion-header-indicator"></span>
                                        </div>
                                        <div id="default_collapseOne1" class="collapse accordion_body"
                                            data-bs-parent="#accordion-one">
                                            <div class="payment-details accordion-body-text">
                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Sat.F.{{ $time->sat_from }}</span>
                                                    <span class="font-w500">Sat.T.{{ $time->sat_to }}</span>
                                                    <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="sat"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->sat_from }}"
                                                        data-to="{{ $time->sat_to }}">Edit</button>
                                                       

                                                </div>

                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Sun.F.{{ $time->sun_from }}</span>
                                                    <span class="font-w500">Sun.T.{{ $time->sun_to }}</span>
                                                    <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="sun"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->sun_from }}"
                                                        data-to="{{ $time->sun_to }}">Edit</button>
                                                        
                                                </div>
                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Mon.F.{{ $time->mon_from }}</span>
                                                    <span class="font-w500">Mon.T.{{ $time->mon_to }}</span>
                                                    <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="mon"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->mon_from }}"
                                                        data-to="{{ $time->mon_to }}">Edit</button>
                                                       
                                                </div>

                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Tue.F.{{ $time->tue_from }}</span>
                                                    <span class="font-w500">Tue.T.{{ $time->tue_to }}</span>
                                                    <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="tue"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->tue_from }}"
                                                        data-to="{{ $time->tue_to }}">Edit</button>
                                                      

                                                </div>




                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Wed.F.{{ $time->wed_from }}</span>
                                                    <span class="font-w500">Wed.T.{{ $time->wed_to }}</span>
                                                    <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="wed"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->wed_from }}"
                                                        data-to="{{ $time->wed_to }}">Edit</button>
                                                       
                                                </div>
                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Thu.F.{{ $time->thu_from }}</span>
                                                    <span class="font-w500">Thu.T.{{ $time->thu_to }}</span>
                                                    <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="thu"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->thu_from }}"
                                                        data-to="{{ $time->thu_to }}">Edit</button>
                                                       
                                                </div>
                                                <div class="info me-3 mb-3">
                                                    <span class="font-w500">Fri.F.{{ $time->fri_from }}</span>
                                                    <span class="font-w500">Fri.T.{{ $time->fri_to }}</span>
                                                   <button class="btn btn-danger btn-xxs shadow edit-btn"
                                                        data-bs-toggle="modal" data-id="{{ $time->id }}"
                                                        data-dayofweek="fri"
                                                        data-datestart="{{ $time->date_start }}"
                                                        data-dateend="{{ $time->date_end }}"
                                                        data-from="{{ $time->fri_from }}"
                                                        data-to="{{ $time->fri_to }}">Edit</button>
                                                        
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    <!-- Modal -->
                    <div class="modal fade" id="sendMessageModal">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Time Edit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

										<form class="comment-form" method="post" action="{{ route('record_update',$Restaurant->id) }}" autocomplete="off"
										enctype="multipart/form-data">
										@csrf
                                        <div class="row">
                                            <div class="col-6 col-sm-6 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" id="dateStart" disabled name="dateStart"
                                                        type="date" name="input1">
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-6 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" id="dateEnd" disabled name="dateEnd"
                                                        type="date" name="input2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 col-sm-4 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" style="background: #f72b50; color:#fff"
                                                        value="OLD" type="text" name="input1" disabled>
                                                </div>
                                            </div>

                                            <div class="col-6 col-sm-4 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" id="timeFrom" disabled type="time"
                                                        name="input1">
                                                    <input type="hidden" name="id" id="timeId">
                                                    <input type="hidden" name="dayofweek" id="dayofweek">
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" id="timeTo" disabled type="time"
                                                        name="input2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 col-sm-4 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" value="New"
                                                        style="background: #f72b50; color:#fff" type="text"
                                                        name="input1" disabled>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" value="9.00" type="time"
                                                        name="new_time_start">
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 mb-2">
                                                <div class="mb-3">
                                                    <input class="form-control" value="6.00" type="time"
                                                        name="new_time_end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">



                                            <div class="col-lg-12">
                                                <div class="mb-3 mb-0">
                                                    <input type="submit" value="Confirm" class="submit btn btn-danger"
                                                        name="submit">
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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // When the user clicks on the edit button, this function will be triggered
            $('.edit-btn').on('click', function() {
                // Get data attributes from the clicked button
                var timeId = $(this).data('id');
                var dateStart = $(this).data('datestart');
                var dateEnd = $(this).data('dateend');
                var timeFrom = $(this).data('from');
                var timeTo = $(this).data('to');
                var dayofweek = $(this).data('dayofweek');
                console.log(dayofweek);
                
                // Populate the modal fields with the fetched data
                $('#timeId').val(timeId); // assuming you have inputs with these IDs in your modal
                $('#dateStart').val(dateStart);
                $('#dateEnd').val(dateEnd);
                $('#timeFrom').val(timeFrom);
                $('#timeTo').val(timeTo);
                $('#dayofweek').val(dayofweek);

                // Show the modal
                $('#sendMessageModal').modal('show');
            });
        });
    </script>

@endsection
@section('js')
@endsection
