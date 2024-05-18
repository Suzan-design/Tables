@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management - Cancelled Reservations
@stop
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center mb-3">
            <div class="mb-3 me-auto">
                <div class="card-tabs style-1 mt-3 mt-sm-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" data-bs-toggle="tab" id="pending-tab"
                                data-bs-target="#pending" role="tab">Pending</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" id="accepted-tab"
                                data-bs-target="#accepted" role="tab">Accepted</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" id="rejected-tab"
                                data-bs-target="#rejected" role="tab">Rejected</a>
                        </li> --}}

                    </ul>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-12 tab-content">
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="table-responsive fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example5">
                            <thead>
                                <tr>
                                    <th>email</th>
                                    <th>phone</th>
                                    <th>reservation_date</th>
                                    <th>request_date</th>
                                    <th>cancel_date</th>
                                    <th>payment_method</th>
                                    <th>amount_paid</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($cancelled_reservations as $reservations)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td><span class="badge light badge-danger">{{ $reservations->email }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge light badge-warning">
                                                    {{ $reservations->phone }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->reservation_date }} -
                                                {{ $reservations->reservation_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->date_request }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->date_cancel }}
                                            </span>
                                        </td>
                                        <td><span
                                                class="badge light badge-danger">{{ $reservations->payment_method }}</span>
                                        <td><span class="badge light badge-danger">{{ $reservations->amount_paid }}</span>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-danger light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                                            </circle>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('Cancelled_Details', $reservations->id) }}"></i>Details</a>
                                                        <a class="dropdown-item"
                                                        href="{{ route('Cancell_accept', $reservations->id) }}"></i>Accept</a>
                                                        <a class="dropdown-item"
                                                        href="{{ route('Cancelled_reject', $reservations->id) }}"></i>Reject</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                    <div class="table-responsive fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example5">
                            <thead>
                                <tr>

                                    <th>email</th>
                                    <th>phone</th>
                                    <th>reservation_date</th>
                                    <th>request_date</th>
                                    <th>cancel_date</th>
                                    <th>payment_method</th>
                                    <th>amount_paid</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($accepted_reservations as $reservations)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>



                                        <td><span class="badge light badge-danger">{{ $reservations->email }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge light badge-warning">
                                                    {{ $reservations->phone }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->reservation_date }} -
                                                {{ $reservations->reservation_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->date_request }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->date_cancel }}
                                            </span>
                                        </td>
                                        <td><span
                                                class="badge light badge-danger">{{ $reservations->payment_method }}</span>
                                        <td><span class="badge light badge-danger">{{ $reservations->amount_paid }}</span>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-danger light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                                            </circle>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('Cancelled_Details', $reservations->id) }}"></i>Details</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <div class="table-responsive fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example5">
                            <thead>
                                <tr>

                                    <th>email</th>
                                    <th>phone</th>
                                    <th>reservation_date</th>
                                    <th>request_date</th>
                                    <th>cancel_date</th>
                                    <th>payment_method</th>
                                    <th>amount_paid</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($accepted_reservations as $reservations)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>



                                        <td><span class="badge light badge-danger">{{ $reservations->email }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge light badge-warning">
                                                    {{ $reservations->phone }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->reservation_date }} -
                                                {{ $reservations->reservation_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->date_request }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $reservations->date_cancel }}
                                            </span>
                                        </td>
                                        <td><span
                                                class="badge light badge-danger">{{ $reservations->payment_method }}</span>
                                        <td><span class="badge light badge-danger">{{ $reservations->amount_paid }}</span>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-danger light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                                            </circle>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('Cancelled_Details', $reservations->id) }}"></i>Details</a>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event delegation for offer links
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.addEventListener('click', function(event) {
                    if (event.target.classList.contains('offer-link')) {
                        event.preventDefault();
                        const id = event.target.getAttribute('data-id');
                        document.querySelector(`.offer-form[data-id="${id}"]`).submit();
                    }
                    if (event.target.classList.contains('table-link')) {
                        event.preventDefault();
                        const id = event.target.getAttribute('data-id');
                        document.querySelector(`.table-form[data-id="${id}"]`).submit();
                    }
                    if (event.target.classList.contains('reservation-link')) {
                        event.preventDefault();
                        const id = event.target.getAttribute('data-id');
                        document.querySelector(`.reservation-form[data-id="${id}"]`).submit();
                    }
                    if (event.target.classList.contains('item-link')) {
                        event.preventDefault();
                        const id = event.target.getAttribute('data-id');
                        document.querySelector(`.item-form[data-id="${id}"]`).submit();
                    }
                    if (event.target.classList.contains('cancel-link')) {
                        event.preventDefault();
                        const id = event.target.getAttribute('data-id');
                        document.querySelector(`.cancel-form[data-id="${id}"]`).submit();
                    }
                });
            });
        });
    </script>
@endsection
@section('js')
@endsection
