@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/calender.css') }}" rel="stylesheet">
@endsection
@section('title')
    Restaurants Management - Reservations
@stop
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)">Notifications</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Browse</a>
                </li>
            </ol>
        </div>

        <!-- row -->
        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header d-block">
                        <h4 class="card-title">Notifications Browse</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach (auth()->user()->Notifications as $notification)
                            <div class="col-xl-12">
                                <div class="alert alert-primary left-icon-big alert-dismissible fade show">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                        <span><i class="mdi mdi-btn-close"></i></span>
                                    </button>
                                    <div class="media">
                                        <div class="alert-left-icon-big">
                                            <span><i class="mdi mdi-email-alert"></i></span>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-1 mb-2">
                                                {{ $notification->data['title'] }}
                                            </h6>
                                            <p class="mb-0">
                                                {{ $notification->data['description'] }}
                                            </p>
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


@endsection
@section('js')
@endsection
