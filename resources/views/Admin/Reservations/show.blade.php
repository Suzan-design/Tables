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
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Details</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Reservation</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="card mt-3">
                    <div class="card-header"> Date: {{$reservation->reservation_date}} <span class="float-end">
                            <strong>Status:</strong>@if($reservation->status =='scheduled')
                                                                Available
                                                                @elseif($reservation->status =='next')
                                                               Not Available
                                                                @else
                                                                {{$reservation->status}}
                                                                @endif</span> </div>
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="mt-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <h6>Customer:</h6>
                                <div> <strong>@if($reservation->status !='scheduled'){{$reservation->first_name  ?? $reservation->user->firstname }} {{$reservation->user->lastname ?? $reservation->last_name }} @endif</strong> </div>
                                <div>Name: @if($reservation->status !='scheduled'){{$reservation->first_name  ?? $reservation->user->firstname }} {{$reservation->user->lastname ?? $reservation->last_name }} @endif</div>
                                <div>Phone: @if($reservation->status !='scheduled'){{$reservation->user->phone ?? $reservation->phone_number }} @endif</div>
                            </div>
                            <div class="mt-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <h6>Table:</h6>
                                <div> <strong>{{$reservation->table->number}}</strong> </div>
                                <div>size: {{$reservation->table->size}}</div>
                                <div>location: {{$reservation->table->location}}</div>
                                <div>type: {{$reservation->table->type}}</div>

                            </div>
                            <div
                                class="mt-4 col-xl-6 col-lg-6 col-md-12 col-sm-12 d-flex justify-content-lg-end justify-content-md-center justify-content-xs-start">
                                <div class="row align-items-center">
                                    <div class="col-sm-9">
                                        <div class="brand-logo mb-3">
                                           
                                            <img class="logo-compact" width="110" src="{{ URL::asset('dashboard/images/logo.jpg') }}"
                                                alt="">
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>reservation time</th>
                                        <th>reservation time end</th>
                                        <th class="right">payment method</th>
                                        <th class="right">Deposite</th>
                                        <th class="right">App comission</th>
                                        <th class="center">promocode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="left strong">{{$reservation->reservation_time}}</td>
                                        <td class="left">{{$reservation->reservation_time_end}}</td>
                                        <td class="right">{{$reservation->payment_method}}</td>
                                        <td class="right">{{$reservation->Restaurant->Deposite_value}}</td>
                                        <td class="right">{{$reservation->Restaurant->taxes}}</td>
                                        <td class="center">{{$reservation->promocode}}</td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>first name</th>
                                        <th class="right">last name</th>
                                        <th>phone number</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="left strong">@if($reservation->status !='scheduled'){{$reservation->first_name  ?? $reservation->user->firstname }} @endif</td>
                                        <td class="left">@if($reservation->status !='scheduled'){{$reservation->last_name  ?? $reservation->user->lastname }}@endif</td>
                                        <td class="right">@if($reservation->status !='scheduled') {{$reservation->user->phone ?? $reservation->phone_number }}@endif</td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-sm-5"> </div>
                            {{-- <div class="col-lg-4 col-sm-5 ms-auto">
                                <table class="table table-clear">
                                    <tbody>
                                        <tr>
                                            <td class="left"><strong>Subtotal</strong></td>
                                            <td class="right">$8.497,00</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>Discount (20%)</strong></td>
                                            <td class="right">$1,699,40</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>VAT (10%)</strong></td>
                                            <td class="right">$679,76</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>Total</strong></td>
                                            <td class="right"><strong>$7.477,36</strong><br>
                                                <strong>0.15050000 BTC</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('js')
@endsection
