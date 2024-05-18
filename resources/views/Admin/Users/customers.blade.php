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
    Restaurants Management - Customers
@stop
@section('content')
    <div class="container-fluid">

        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Users</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-fixed-height table-sm mb-0 table-striped">
                                    <tr>
                                        <th class=" pe-3">
                                            <div class="form-check custom-checkbox mx-2">        
                                                <label for="checkAll">ID</label>
                                            </div>
                                        </th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>State</th>
                                        <th>Action</th>
                                    </tr>
                                    @php
                                        $i = 0;
                                         use Carbon\Carbon;
                                    @endphp
                                    @foreach ($users as $user)
                                        @php
                                            $i++;
                                        @endphp
                                        <tr class="btn-reveal-trigger">
                                            <td class="py-2">
                                                <div class="form-check custom-checkbox mx-2">
                                          
                                                    <label class="form-check-label"
                                                        for="checkbox12">#{{ $i }}</label>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <a href="#">
                                                    <div class="media d-flex align-items-center">
                                                        <div class="avatar avatar-xl me-2">
                                                            <img class="rounded-circle img-fluid" src="{{URL::asset('images/avatar/1.png')}}"
                                                                alt="" width="30">
                                                        </div>
                                                        <div class="media-body">
                                                            <h5 class="mb-0 fs--1">{{ $user->firstname }} {{$user->lastname}}</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="py-2"><a href="mailto:antony@example.com">{{ $user->email }}</a>
                                            </td>
                                            <td class="py-2"> <a href="tel:9013243127">{{ $user->phone }}</a></td>
                                            <td class="py-2"> <a href="tel:9013243127"> {{ Carbon::parse($user->birthDate)->age }} years old</a></td>
                                            <td class="py-2"> <a href="tel:9013243127">{{ $user->gender }}</a></td>

                                    <td><span class="badge badge-danger light">{{$user->State}}</span>
                                    
                                                </td>
                                    <td class="py-2 text-end">
                    										<div class="dropdown">
                                        <button class="btn btn-primary tp-btn-light sharp" type="button" data-bs-toggle="dropdown">
                                        <span class="fs--1">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewbox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                            <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                            <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                            </g></svg>
                                        </span>
                                        </button>
                    											<div class="dropdown-menu dropdown-menu-end border py-0">
                    												<div class="py-2">
                                               @if ($user->isBlocked)
                                                    <form method="post" action="{{ route('Unblock_user', $user->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-user-slash"></i> UnBlock</button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('block_user', $user->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-user-slash"></i> Block</button>
                                                    </form>
                                                @endif
                                                
                                                <form method="post" action="{{ route('delete_user', $user->id) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa fa-trash"></i> Delete</button>
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
