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
    Restaurants Management - Statistics
@stop
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="card">
                            <div class="card-header d-block d-sm-flex border-0">
                                <div class="me-3">
                                    <button type="button" class="btn btn-rounded btn-outline-danger">
                                        <h4 class="card-title mb-2">Reservations Management</h4>
                                    </button>
                                    {{-- <span class="fs-12"></span> --}}
                                </div>
                                </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                                type="button" role="tab" aria-controls="pending" aria-selected="true">Pending
                                Reservations</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted"
                                type="button" role="tab" aria-controls="accepted" aria-selected="false">Accepted
                                Reservations</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                                type="button" role="tab" aria-controls="rejected" aria-selected="false">Rejected
                                Reservations</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled"
                                type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled
                                Reservations</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">

                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th class=" pe-3">
                                                        ID
                                                    </th>
                                                    <th>Restaurant Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Reservation time</th>
                                                    <th>Duration</th>
                                                    <th>Reservation date</th>
                                                    <th>Deposit</th>
                                                    <th>Status</th>
                                                    @if(auth()->user()->roleName=='staff')
                                                    <th>Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody id="pendingReservations">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($reservations as $reservation)
                                                    <tr class="btn-reveal-trigger">
                                                        <td class="py-2">
                                                            {{ $reservation->id }}
                                                        </td>
                                                        <td class="py-3">
                                                            <a href="#">
                                                                <div class="media d-flex align-items-center">
                                                                    <div class="avatar avatar-xl me-2">
                                                                        <img class="rounded-circle img-fluid"
                                                                            src="images/avatar/1.png" alt=""
                                                                            width="30">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5 class="mb-0 fs--1">
                                                                            {{ $reservation->Restaurant->name }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="py-2">@if ($reservation->user)
                                                            {{ $reservation->user->firstname }}
                                                            {{ $reservation->user->lastname }}
                                                        @endif
                                                        </td>
                                                        <td class="py-2"> <a
                                                                href="tel:9013243127">{{ $reservation->reservation_time }}</a>
                                                        </td>

                                                        <td><span
                                                                class="badge badge-danger light">{{ $reservation->duration }}</span>

                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->reservation_date }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->Restaurant->Deposite_value }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->status }}</span>
                                                        </td>
                                                        @if(auth()->user()->roleName=='staff')
                                                        <td class="py-2 text-end">
                                                            <span class="badge badge-danger light">
                                                                <li class="nav-item" role="presentation">
                            <a class="nav-link"
                                type="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#accept" role="tab" aria-controls="accepted" aria-selected="false">Accept</a>
                        </li>
                        <div class="modal fade none-border" id="accept">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h4 class="modal-title"><strong>Accept Reservation</strong></h4>
                                  </div>
                                  <div class="modal-body">
                                      <form method="post" action="{{ route('accept') }}" autocomplete="off"
                                          enctype="multipart/form-data">
                                          @csrf
                                          <div class="row">
                                              <div class="col-md-12">
                                              <input type="hidden" name="res_id" value="{{ $reservation->id }}">
                                                  <label class="control-label form-label">Table</label>
                                                  <select class="default-select wide form-control" name="table_id"
                                                    id="validationCustom05" required>
                                                    @foreach ($tables as $table)
                                                    <option value="{{$table->id}}">{{$table->location}} {{$table->size}} {{$table->type}}</option>
                                                    @endforeach
                                                    </select>
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
                                                            </span>
                                                            <span>
                                                                <a href="{{ route('reject', $reservation->id) }}"
                                                                    class="btn btn-danger">Reject</a>
                                                                    </span>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">

                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th class=" pe-3">
                                                        ID
                                                    </th>
                                                    <th>Restaurant Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Reservation time</th>
                                                    <th>Duration</th>
                                                    <th>Reservation date</th>
                                                    <th>Deposite</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="acceptedReservations">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($accepted_reservations as $reservation)
                                                    <tr class="btn-reveal-trigger">
                                                        <td class="py-2">
                                                            {{ $reservation->id }}
                                                        </td>
                                                        <td class="py-3">
                                                            <a href="#">
                                                                <div class="media d-flex align-items-center">
                                                                    <div class="avatar avatar-xl me-2">
                                                                        <img class="rounded-circle img-fluid"
                                                                            src="images/avatar/1.png" alt=""
                                                                            width="30">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5 class="mb-0 fs--1">
                                                                            {{ $reservation->Restaurant->name }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="py-2">@if ($reservation->user)
                                                            {{ $reservation->user->firstname }}
                                                            {{ $reservation->user->lastname }}
                                                        @endif
                                                        </td>
                                                        <td class="py-2"> <a
                                                                href="tel:9013243127">{{ $reservation->reservation_time }}</a>
                                                        </td>

                                                        <td><span
                                                                class="badge badge-danger light">{{ $reservation->duration }}</span>

                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->reservation_date }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->Restaurant->Deposite_value }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->status }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">

                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th class=" pe-3">
                                                        ID
                                                    </th>
                                                    <th>Restaurant Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Reservation time</th>
                                                    <th>Duration</th>
                                                    <th>Reservation date</th>
                                                    <th>Deposite</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rejectedReservations">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($rejected_reservations as $reservation)
                                                    <tr class="btn-reveal-trigger">
                                                        <td class="py-2">
                                                            {{ $reservation->id }}
                                                        </td>
                                                        <td class="py-3">
                                                            <a href="#">
                                                                <div class="media d-flex align-items-center">
                                                                    <div class="avatar avatar-xl me-2">
                                                                        <img class="rounded-circle img-fluid"
                                                                            src="images/avatar/1.png" alt=""
                                                                            width="30">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5 class="mb-0 fs--1">
                                                                            {{ $reservation->Restaurant->name }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="py-2">@if ($reservation->user)
                                                            {{ $reservation->user->firstname }}
                                                            {{ $reservation->user->lastname }}
                                                        @endif
                                                        </td>
                                                        <td class="py-2"> <a
                                                                href="tel:9013243127">{{ $reservation->reservation_time }}</a>
                                                        </td>

                                                        <td><span
                                                                class="badge badge-danger light">{{ $reservation->duration }}</span>

                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->reservation_date }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->Restaurant->Deposite_value }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->status }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">

                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th class=" pe-3">
                                                        ID
                                                    </th>
                                                    <th>Restaurant Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Reservation time</th>
                                                    <th>Duration</th>
                                                    <th>Reservation date</th>
                                                    <th>Deposite</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="acceptedReservations">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($cancelled_reservations as $reservation)
                                                    <tr class="btn-reveal-trigger">
                                                        <td class="py-2">
                                                            {{ $reservation->id }}
                                                        </td>
                                                        <td class="py-3">
                                                            <a href="#">
                                                                <div class="media d-flex align-items-center">
                                                                    <div class="avatar avatar-xl me-2">
                                                                        <img class="rounded-circle img-fluid"
                                                                            src="images/avatar/1.png" alt=""
                                                                            width="30">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5 class="mb-0 fs--1">
                                                                            {{ $reservation->Restaurant->name }}
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="py-2">@if ($reservation->user)
                                                            {{ $reservation->user->firstname }}
                                                            {{ $reservation->user->lastname }}
                                                        @endif
                                                        </td>
                                                        <td class="py-2"> <a
                                                                href="tel:9013243127">{{ $reservation->reservation_time }}</a>
                                                        </td>

                                                        <td><span
                                                                class="badge badge-danger light">{{ $reservation->duration }}</span>

                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->reservation_date }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->Restaurant->Deposite_value }}</span>
                                                        </td>
                                                        <td class="py-2 text-end">
                                                            <span
                                                                class="badge badge-danger light">{{ $reservation->status }}</span>
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
