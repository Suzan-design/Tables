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
    Restaurants Management - Restaurants Data
@stop
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-end mb-3">
            <div class="card-tabs style-1 mt-3 mt-sm-0">
                <ul role="tablist">
                    @can('Crud Restaurants')
                        <li class="nav-item">
                            <a href="{{ route('Restaurants.create') }}" class="btn btn-primary" alt="Add Restaurant"
                                id="animated-img1">
                                <i class="las la-plus ms-3" style="margin-right:10px"></i> Add Restaurant
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>

        <div class="col-xl-12 tab-content">
            <div class="tab-pane fade show active" id="Restaurants" role="tabpanel" aria-labelledby="Restaurants-tab">
                <div class="table-responsive  table-container fs-14">
                    <table class="table card-table display mb-4 dataTablesCard text-black"
                        id="example5">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Account</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Cuisine en</th>
                                <th>Cuisine ar</th>
                                <th>Status</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($all as $Restaurant)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td><span>#{{ $i }}</span></td>
                                    <td>
                                        <span class="text-nowrap">{{ $Restaurant->name }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ URL::asset('dashboard/images/avatar/R.jpg') }}" alt=""
                                                class="rounded-circle me-3" width="50">
                                            <div>
                                                <h6 class="fs-16 mb-0 text-nowrap">
                                                    {{ $Restaurant->staff->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge light badge-danger">{{ $Restaurant->phone_number }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge light badge-warning">
                                                {{ $Restaurant->location->state }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $Restaurant->Cuisine->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $Restaurant->Cuisine->ar_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($Restaurant->status == 'active')
                                                <i class="fa fa-circle text-success me-1"></i>
                                            @else
                                                <i class="fa fa-circle text-danger me-1"></i>
                                            @endif
                                            {{ $Restaurant->status }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-danger light sharp"
                                                data-bs-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
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
                                            <div class="dropdown-menu dropdown-menu floating-dropdown">
                                                <a class="dropdown-item"
                                                    href="{{ route('Restaurants.show', $Restaurant->id) }}">Profile
                                                    Details</a>
                                                    <a class="dropdown-item" href="{{ route('records_reservations', $Restaurant->id) }}" >Times Management</a>
                                                <a class="dropdown-item table-link" data-id="{{ $Restaurant->id }}">Tables
                                                    Management</a>
                                                <form action="{{ route('tables.index') }}" method="GET"
                                                    class="table-form" data-id="{{ $Restaurant->id }}"
                                                    style="display: none;">
                                                    <input type="hidden" name="res_id" value="{{ $Restaurant->id }}">
                                                    <!-- Submit button here -->
                                                </form>

                                                <a class="dropdown-item offer-link" href="#"
                                                    data-id="{{ $Restaurant->id }}">Offers Management</a>
                                                <form action="{{ route('offers.index') }}" method="GET"
                                                    class="offer-form" data-id="{{ $Restaurant->id }}"
                                                    style="display: none;">
                                                    <input type="hidden" name="res_id" value="{{ $Restaurant->id }}">
                                                    <!-- Submit button here -->
                                                </form>
                                                <a class="dropdown-item item-link" href="#"
                                                    data-id="{{ $Restaurant->id }}">MenuItems Management</a>
                                                <form action="{{ route('items.index') }}" method="GET" class="item-form"
                                                    data-id="{{ $Restaurant->id }}" style="display: none;">
                                                    <input type="hidden" name="res_id" value="{{ $Restaurant->id }}">
                                                    <!-- Submit button here -->
                                                </form>
                                                <a class="dropdown-item reservation-link" href="#"
                                                    data-id="{{ $Restaurant->id }}">Reservations Details</a>
                                                <form action="{{ route('Restaurant_reservations', $Restaurant->id) }}"
                                                    method="Post" class="reservation-form" data-id="{{ $Restaurant->id }}"
                                                    style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="res_id" value="{{ $Restaurant->id }}">

                                                </form>





                                                <a class="dropdown-item"
                                                    href="{{ route('Restaurants.edit', $Restaurant->id) }}"></i>edit</a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="event.preventDefault();
                                                                 document.getElementById('destroy-form-{{ $Restaurant->id }}').submit();">
                                                    Delete
                                                </a>
                                                <form id="destroy-form-{{ $Restaurant->id }}"
                                                    action="{{ route('Restaurants.destroy', $Restaurant->id) }}"
                                                    method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                                @if ($Restaurant->status == 'active')
                                                    <a class="dropdown-item"
                                                        href="{{ route('act_inact__Restaurant', $Restaurant->id) }}"></i>inActive</a>
                                                @else
                                                    <a class="dropdown-item"
                                                        href="{{ route('act_inact__Restaurant', $Restaurant->id) }}"></i>Active</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

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
