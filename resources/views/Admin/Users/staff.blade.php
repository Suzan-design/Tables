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
Restaurants Management - Staff
@stop
@section('content')
<div class="container-fluid">

	<div class="row page-titles">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Users</a></li>
			<li class="breadcrumb-item"><a href="javascript:void(0)">Restaurant account</a></li>
		</ol>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive table-container">
						<table class="table table-sm mb-0 table-striped">
							<thead>

								<tr>
									<th class=" pe-3">
										ID
									</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th class=" ps-5" style="min-width: 200px;">Status
									</th>
									<th>Restaurant</th>
									{{-- <th></th> --}}
								</tr>
							</thead>
                                 @php
use App\Models\Restaurant;
@endphp
							<tbody id="customers">
								@php
                                $i = 0;
                                @endphp
                                @foreach ($users as $user)
                                    @php
                                      
                                  
                                      // Make sure $i is defined and used properly elsewhere
                                      $i++;
                                  
                                      // Fetch the first restaurant for the current user
                                      $restaurant = Restaurant::where('user_id', $user->id)->first();
                                  @endphp
								<tr class="btn-reveal-trigger">
									<td class="py-2">
										<div class="form-check custom-checkbox mx-2">

											<label class="form-check-label" for="checkbox12">#{{ $i }}</label>
										</div>
									</td>
									<td class="py-3">
										<a href="#">
											<div class="media d-flex align-items-center">
                         @if($restaurant)
												@foreach ($restaurant->images as $image)
                                    @if ($image->type === 'logo')
                                        <img src="{{ asset($image->filename) }}" alt="image" class="me-3 rounded"
                                            width="75">
                                    @endif
                                @endforeach
                                @endif
												<div class="media-body">
													<h5 class="mb-0 fs--1">{{$user->name}}</h5>
												</div>
											</div>
										</a>
									</td>
									<td class="py-2"><a href="mailto:antony@example.com">{{$user->email}}</a></td>
									<td class="py-2"> <a href="tel:9013243127">{{$user->phone}}</a></td>

									<td class="py-2 ps-5">
									<span class="badge light badge-success">
														<i class="fa fa-circle text-success me-1"></i>
														{{$user->status}}
													</span>
									</td>

									<td class="py-2">
                                                   @if($restaurant)
									<a href="{{route('Restaurants.show',$restaurant->id)}}"
									<span class="badge badge-danger">{{$user->name_restaurant}}</span>
                  @endif
									</td>
									{{-- <td class="py-2 text-end">
										<div class="dropdown"><button class="btn btn-primary tp-btn-light sharp" type="button" data-bs-toggle="dropdown"><span class="fs--1"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg></span></button>
											<div class="dropdown-menu dropdown-menu-end border py-0">
												<div class="py-2"><a class="dropdown-item" href="#!">Edit</a><a class="dropdown-item text-danger" href="#!">Delete</a></div>
											</div>
										</div>
									</td> --}}
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