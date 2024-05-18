@extends('layouts.master')
@section('css')
<style>
     .dropdown-menu {
    position: absolute !important;
    z-index: 1000 !important; /* Adjust z-index as necessary */
}

.table-container {
    overflow: visible !important; /* Only change if it doesn't disrupt other layout aspects */
}

    </style>
@endsection
@section('title')
    Restaurants Management - Achievements System
@stop
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center mb-3">
            <div class="mb-3 me-auto">
                <div class="card-tabs style-1 mt-3 mt-sm-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" data-bs-toggle="tab" id="transaction-tab"
                                data-bs-target="#invitation_sender" role="tab">Achievements</a>
                        </li>

                        {{-- <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" id="Pending-tab" data-bs-target="#statistics" role="tab">Statistics</a>
                    </li> --}}

                    </ul>
                </div>
            </div>
            <a href="{{ route('invitations.create') }}" class="btn btn-outline-primary mb-3"><i
                    class="fa fa-calendar me-3 scale3"></i>Generate Achievement</a>
        </div>
        <div class="row">
            <div class="col-xl-12 tab-content">
                <div class="tab-pane fade show active" id="invitation_sender" role="tabpanel"
                    aria-labelledby="transaction-tab">
                    <div class="table-responsive table-container fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example5">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Expire_at</th>
                                    <th>discount %</th>
                                    <th>Limit</th>
                                    <th>type</th>
                                    <th>target</th>
                                    <th>image</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach ($invitations as $item)
                                    <tr>
                                        <td class="py-2">#{{ $counter }}</td>
                                        <td><a href="javascript:void(0)"
                                                class="btn btn-warning btn-rounded light">{{ $item->expire }}</a></td>
                                        <td><span>{{ $item->discount }}</span></td>
                                        <td><a href="javascript:void(0)"
                                                class="btn btn-danger btn-rounded light">{{ $item->limit }}</a></td>

                                        <td><span>{{ $item->type }}</span></td>
                                        <td><span>{{ $item->target }}</span></td>
                                        <td><span>
                                            <img src="{{ asset($item->image) }}"
                                            alt="image" class="me-3 rounded" width="75">
                                            </span></td>

                                        <td>
                                            <div class="dropdown dropstart">
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
                                                <div class="dropdown-menu">

                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="event.preventDefault();
                                                    document.getElementById('destroy-form-{{ $item->id }}').submit();"><i
                                                            class="las la-times-circle text-danger scale5 me-3"></i>Delete</a>

                                                    <form id="destroy-form-{{ $item->id }}"
                                                        action="{{ route('invitations.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>


                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="Pending-tab">
                    <div class="table-responsive fs-14">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card progress-card">
                                    <div class="card-body d-flex">
                                        <div class="me-auto">
                                            <h4 class="card-title">Total Transactions</h4>
                                            <div class="d-flex align-items-center">
                                                <h2 class="fs-38 mb-0">98k</h2>
                                                <div class="text-success transaction-caret">
                                                    <i class="fas fa-sort-up"></i>
                                                    <p class="mb-0">+0.5%</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical-bottom"
                                            style="min-height:110px;min-width:10px;">
                                            <div class="progress-bar bg-primary" style="width:10px; height:40%;"
                                                role="progressbar">
                                                <span class="sr-only">40% Complete</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical-bottom"
                                            style="min-height:110px;min-width:10px;">
                                            <div class="progress-bar bg-primary" style="width:10px; height:55%;"
                                                role="progressbar">
                                                <span class="sr-only">55% Complete</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical-bottom"
                                            style="min-height:110px;min-width:10px;">
                                            <div class="progress-bar bg-primary" style="width:10px; height:80%;"
                                                role="progressbar">
                                                <span class="sr-only">80% Complete</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical-bottom"
                                            style="min-height:110px;min-width:10px;">
                                            <div class="progress-bar bg-primary" style="width:10px; height:50%;"
                                                role="progressbar">
                                                <span class="sr-only">50% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Invoice Remaining</h4>
                                        <div class="d-flex align-items-center">
                                            <div class="me-auto">
                                                <div class="progress mt-4" style="height:10px;">
                                                    <div class="progress-bar bg-primary progress-animated"
                                                        style="width: 45%; height:10px;" role="progressbar">
                                                        <span class="sr-only">60% Complete</span>
                                                    </div>
                                                </div>
                                                <p class="fs-16 mb-0 mt-2"><span class="text-danger">-0,8% </span>from
                                                    last month</p>
                                            </div>
                                            <h2 class="fs-38">854</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mt-2">Invoice Sent</h4>
                                        <div class="d-flex align-items-center mt-3 mb-2">
                                            <h2 class="fs-38 mb-0 me-3">456</h2>
                                            <span class="badge badge-success badge-xl">+0.5%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mt-2">Invoice Compeleted</h4>
                                        <div class="d-flex align-items-center mt-3 mb-2">
                                            <h2 class="fs-38 mb-0 me-3">1467</h2>
                                            <span class="badge badge-danger badge-xl">-6.4%</span>
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

@endsection
@section('js')
@endsection
