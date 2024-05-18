@extends('layouts.master')
@section('css')
    <style>
        .badge-rounded {
            border-radius: 20px;
            padding: 10px 23px;
            margin: 3px;
            /* size: 32px; */
        }
    </style>
@endsection
@section('title')
    Restaurants Management - Reservation Details
@stop
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Reservations</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Details</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="card mt-3">
                    <div class="card-header">
                        <span class="badge badge-pill badge-secondary">Details </span>

                        <span class="badge light badge-secondary">
                            <strong>
                                Time : {{ $reservation->reservation_time ?? '-' }}
                            </strong>
                        </span>
                        <a id="statusBadge_{{ $reservation->id }}" href="javascript:void(0)"
                            class="float-end badge badge-rounded badge-outline-danger">
                            <strong>Status:</strong> {{ $reservation->status ?? '-' }}</a>


                    </div>
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="mt-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <h6>User:</h6>


                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            firstname: {{ $reservation->firstname ?? '-' }}
                                        </strong>
                                    </a>

                                </div>

                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            lastname: {{ $reservation->lastname ?? '-' }}
                                        </strong>
                                    </a>
                                </div>


                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            Phone: {{ $reservation->phone ?? '-' }}
                                        </strong>
                                    </a>


                                </div>

                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            email: {{ $reservation->email ?? '-' }}
                                        </strong></a>


                                </div>





                            </div>
                            <div class="mt-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <h6>Table:</h6>



                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            reservation_date : {{ $reservation->reservation_date ?? '-' }}
                                        </strong>
                                    </a>

                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            reservation time : {{ $reservation->reservation_time ?? '-' }}
                                        </strong>
                                    </a>

                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            date request : {{ $reservation->date_request ?? '-' }}
                                        </strong>
                                    </a>

                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            date cancel : {{ $reservation->date_cancel ?? '-' }}
                                        </strong>
                                    </a>

                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="mt-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <h6>Restaurant:</h6>


                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            Deposite information: {{ $reservation->Deposite_information ?? '-' }}
                                        </strong>
                                    </a>

                                </div>

                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            refund policy: {{ $reservation->refund_policy ?? '-' }}
                                        </strong>
                                    </a>
                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            change policy: {{ $reservation->change_policy ?? '-' }}
                                        </strong>
                                    </a>
                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            change policy: {{ $reservation->change_policy ?? '-' }}
                                        </strong>
                                    </a>

                                </div>



                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            deposit: {{ $reservation->deposit ?? '-' }}
                                        </strong></a>
                                </div>

                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            payment method: {{ $reservation->payment_method ?? '-' }}
                                        </strong></a>
                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="badge badge-rounded badge-danger">
                                        <strong>
                                            amount paid: {{ $reservation->amount_paid ?? '-' }}
                                        </strong></a>
                                </div>





                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
@section('js')
@endsection
