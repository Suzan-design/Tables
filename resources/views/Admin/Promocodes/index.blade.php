@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/calender.css') }}" rel="stylesheet">
     <style>
        .floating-dropdown {
            position: fixed !important; /* Use fixed positioning */
            will-change: transform; /* Optimizes animations */
            top: 0% !important; /* Center vertically */
            left: 90% !important; /* Center horizontally */
            transform: translate(-50%, -50%); /* Adjust to exact center */
            max-width: 30px !important; /* Optional: Set a minimum width */
        }
    </style>
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
    Restaurants Management - Promocodes
@stop
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-end mb-3">
            <div class="card-tabs style-1 mt-3 mt-sm-0">
                <ul role="tablist">
                        <li class="nav-item">
                            <a href="{{ route('promocodes.create') }}" class="btn btn-primary" alt="Add Restaurant"
                                id="animated-img1">
                                <i class="las la-plus ms-3" style="margin-right:10px"></i> Add PromoCode
                            </a>
                        </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-fixed-height card-table display mb-4 dataTablesCard">
                                    <tr>
                                        <th class="align-middle">#ID</th>
                                        <th class="align-middle">Code</th>
                                        <th class="align-middle">Start Date</th>
                                        <th class="align-middle"> End Date </th>
                                        <th class="align-middle">discount %</th>
                                        <th class="align-middle ">Limit</th>
                                        <th class="align-middle ">Users</th>
                                        <th class="align-middle ">Restaurants</th>
                                        <th class="no-sort"></th>
                                    </tr>
                                    @foreach ($promocodes as $promocode)
                                        <tr class="btn-reveal-trigger">
                                            <td class="py-2">#{{ $promocode->id }}</td>
                                            <td class="py-2">{{ $promocode->code }}</td>
                                            <td class="py-2">{{ $promocode->start_date }}</td>
                                            <td class="py-2">{{ $promocode->end_date }}</td>
                                            <td class="py-2">
                                                <span
                                                    class="badge badge-success">{{ $promocode->discount }}%<span></span></span>
                                            </td>
                                            <td class="py-2">{{ $promocode->limit }}</td>
                                            <td class="py-2 ">{{ $promocode->num_us }}</td>
                                            <td class="py-2 ">{{ $promocode->num_res }}</td>
                                            <td class="py-2 ">
                                                <div class="dropdown text-sans-serif">
                                                    <button class="btn btn-primary tp-btn-light sharp" type="button"
                                                        id="order-dropdown-9" data-bs-toggle="dropdown"
                                                        data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                                        <span><svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="18px"
                                                                height="18px" viewbox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                                    <circle fill="#000000" cx="5" cy="12"
                                                                        r="2"></circle>
                                                                    <circle fill="#000000" cx="12" cy="12"
                                                                        r="2"></circle>
                                                                    <circle fill="#000000" cx="19" cy="12"
                                                                        r="2"></circle>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                   <div class="dropdown-menu dropdown-menu-end border py-0 floating-dropdown"
    aria-labelledby="order-dropdown-9">
                                                        <div class="py-2">
                                                            <a class="dropdown-item"
                                                                href="{{ route('promocodes.show', $promocode->id) }}">Details</a>

                                                            {{-- <a class="dropdown-item" href="#"
                                                                onclick="event.preventDefault();
                                                                     document.getElementById('inactive-form-{{ $promocode->id }}').submit();">
                                                                inActive
                                                            </a>
                                                            <form id="inactive-form-{{ $promocode->id }}"
                                                                action="{{ route('promocodes_inactive', $promocode->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                            </form> --}}
                                                            <a class="dropdown-item text-danger" href="#"
                                                                onclick="event.preventDefault();
                                                                     document.getElementById('destroy-form-{{ $promocode->id }}').submit();">
                                                                Delete
                                                            </a>
                                                            <form id="destroy-form-{{ $promocode->id }}"
                                                                action="{{ route('promocodes.destroy', $promocode->id) }}"
                                                                method="POST" style="display: none;">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection
@section('css')
    <style>
        .icon-size {
            width: 32px; /* or any desired width */
            height: 32px; /* or any desired height */
            display: block; /* to ensure it takes the full width of the cell */
            margin: 10px ; /* to center the image in the cell */
        }
        .btn-primary{
            margin: 10px ;
            width: 180px;
        }
        th {
            position: relative; /* Allows absolute positioning of pseudo-elements relative to the th */
        }
        th.asc::after {
            content: " ??";
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
        }
        th.desc::after {
            content: " ??";
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.table');
        const headers = table.querySelectorAll('th');
        const tableBody = table.querySelector('tbody');
        const rows = tableBody.querySelectorAll('tr');

        const handleSorting = (rows, index, asc) => {
            return Array.from(rows).sort(function(a, b) {
                const aVal = a.querySelector(`td:nth-child(${index + 1})`).textContent.trim();
                const bVal = b.querySelector(`td:nth-child(${index + 1})`).textContent.trim();

                if (aVal === bVal) return 0;
                if (aVal > bVal) return asc ? 1 : -1;
                return asc ? -1 : 1;
            });
        };

        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const asc = header.classList.toggle('asc');
                const sortedRows = handleSorting(rows, index, asc);
                headers.forEach(h => { h.classList.remove('asc', 'desc'); });
                header.classList.add(asc ? 'asc' : 'desc');
                sortedRows.forEach(row => tableBody.appendChild(row));
            });
        });
    });
</script>