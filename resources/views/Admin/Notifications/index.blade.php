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
    Public Notification
@endsection
@section('content')

    <hr />
    <div class="d-flex align-items-center justify-content-end">
        <a href="{{ route('notification_dashboard') }}" class="btn btn-primary" >Add Notification</a>
    </div>
    
    <div class="container-fluid">
    <div class="col-xl-12 col-xxl-12">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Notification table</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-container p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="">ID</th>
                                <th class="">Title</th>
                                <th class="">description</th>
                                <th class="">target states</th>
                                <th class="">target ages</td>
                                <th class="">target reservations</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($public_notifications->count() > 0)
                                @foreach($public_notifications as $notification)
                                    <tr>
                                        <td class="align-middle">{{ $notification->id }}</td>
                                        <td class="align-middle">{{ $notification->title }}</td>
                                        <td class="align-middle">{{ $notification->description }}</td>
                                        <td class="align-middle">{{ $notification->target_states ?? 'N/A' }}</td>
                                        <td class="align-middle">{{ $notification->target_ages ?? 'N/A' }}</td>
                                        <td class="align-middle">{{ $notification->target_bookings ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="3">Notification not found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

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