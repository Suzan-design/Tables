@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management - Restaurant Tables
@stop
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 col-xxl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header flex-wrap border-0 pb-0 align-items-end">
                                <div class="mb-3 me-3">
                                    <h5 class="fs-20 text-black font-w500">Main Balance</h5>
                                    <span class="text-num text-black fs-36 font-w500">$673,412.66</span>
                                </div>
                                <div class="me-3 mb-3">
                                    <p class="fs-14 mb-1">VALID THRU</p>
                                    <span class="text-black fs-16">08/21</span>
                                </div>
                                <div class="me-3 mb-3">
                                    <p class="fs-14 mb-1">CARD HOLDER</p>
                                    <span class="text-black fs-16">WilliamFacyson</span>
                                </div>
                                <span class="fs-20 text-black font-w500 me-3 mb-3">**** **** **** 1234</span>
                                <div class="dropdown mb-auto">
                                    <a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z"
                                                stroke="#575757" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path
                                                d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z"
                                                stroke="#575757" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path
                                                d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z"
                                                stroke="#575757" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    @can('Crud Tables')
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-gradient-5 progress-animated"
                                        style="width: 50%; height:20px;" role="progressbar">
                                        <span class="sr-only">50% Complete</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                @can('Crud Tables')
                                <h4 class="card-title">
                                    <a href="{{ route('table_add', $res_id) }}" class="btn btn-primary mb-1">Table Add</a>
                                </h4>
                                @endcan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Number</th>
                                                <th scope="col">seating_configuration</th>
                                                <th scope="col">capacity</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tables as $table)
                                                <tr>
                                                    <td># {{ $table->number }}</td>


                                                    <td><span
                                                            class="badge badge-primary">{{ $table->seating_configuration }}</span>
                                                    </td>

                                                    <td><span class="badge badge-danger">{{ $table->capacity }}</span>
                                                    </td>
                                                    <td><span>

                                                            <a href="{{ route('table_reservations') }}" class="me-4"
                                                                data-bs-toggle="tooltip" data-placement="top"
                                                                title="Edit">
                                                                <i class="fas fa-pencil-alt color-muted"></i>
                                                            </a>


                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
@endsection
