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
        <div class="d-flex flex-wrap align-items-center mb-3">
            <div class="mb-3 me-auto">
                <div class="card-tabs style-1 mt-3 mt-sm-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" data-bs-toggle="tab" id="transaction-tab"
                                data-bs-target="#offers" role="tab">Offers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" id="Completed-tab"
                                data-bs-target="#openings" role="tab">New Openings</a>
                        </li>

                    </ul>
                </div>
            </div>
            <a href="javascript:void()" data-bs-toggle="modal"
                data-bs-target="#add_employee"class="btn btn-outline-primary mb-3">
                <i class="fa fa-add me-3 scale3"></i>Offer
                Add</a>

            <a href="javascript:void()" data-bs-toggle="modal"
                data-bs-target="#add_employee_n"class="btn btn-outline-primary mb-3">
                <i class="fa fa-add me-3 scale3"></i>new_opening
                Add</a>
        </div>
        <div class="row">
            <div class="col-xl-12 tab-content">
                <div class="tab-pane fade show active" id="offers" role="tabpanel" aria-labelledby="offers-tab">
                    <div class="table-responsive table-container fs-14">
                        <table class="table table-fixed-height card-table display mb-4 dataTablesCard text-black" id="example5">
                                <tr>
                                    <th>ID</th>
                                    <th>Name En</th>
                                    <th>Name Ar</th>
                                    <th>Description En</th>
                                    <th>Description Ar</th>
                                    <th>old price</th>
                                    <th>new price</th>
                                    <th>services En</th>
                                    <th>services Ar</th>
                                    <th>Status</th>
                                    <th>Operations</th>
                                </tr>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($offers as $offer)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td><span>#{{ $i }}</span></td>
                                        <td>
                                            <span class="text-nowrap">{{ $offer->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-nowrap">{{ $offer->ar_name }}</span>
                                        </td>

                                        <td><span class="badge light badge-danger">{{ $offer->description }}</span>
                                        </td>
                                        <td><span class="badge light badge-danger">{{ $offer->ar_description }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge light badge-warning">
                                                    {{ $offer->price_old }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $offer->price_new }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $offer->featured }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $offer->ar_featured }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($offer->status == 'active')
                                                    <i class="fa fa-circle text-success me-1"></i>
                                                @else
                                                    <i class="fa fa-circle text-danger me-1"></i>
                                                @endif
                                                {{ $offer->status }}
                                            </div>
                                        </td>
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
                                                        href="{{ route('offers.show', $offer->id) }}">Offer
                                                        Details</a>

                                                    {{-- <a class="dropdown-item edit-offer" href="javascript:void(0);"
                                                        class="btn btn-primary mb-1" data-bs-toggle="modal"
                                                        data-id="{{ $offer->id }}"
                                                        data-bs-target="#editMessageModal">Edit</a> --}}
                                                    <a class="dropdown-item" href="#"
                                                        onclick="event.preventDefault();
                                                                 document.getElementById('destroy-form-{{ $offer->id }}').submit();">
                                                        Delete
                                                    </a>
                                                    <form id="destroy-form-{{ $offer->id }}"
                                                        action="{{ route('offers.destroy', $offer->id) }}" method="POST"
                                                        style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    @if ($offer->status == 'active')
                                                        <a class="dropdown-item"
                                                            href="{{ route('act_inact__offer', $offer->id) }}"></i>inActive</a>
                                                    @else
                                                        <a class="dropdown-item"
                                                            href="{{ route('act_inact__offer', $offer->id) }}"></i>Active</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach



                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="openings" role="tabpanel" aria-labelledby="openings-tab">
                    <div class="table-responsive table-container fs-14">
                        <table class="table table-fixed-height card-table display mb-4 dataTablesCard text-black" id="example5">
                                <tr>
                                    <th>ID</th>
                                    <th>Name En</th>
                                    <th>Name Ar</th>
                                    <th>Description En</th>
                                    <th>Description Ar</th>
                                    <th>start date</th>
                                    <th>Status</th>
                                    <th>Operations</th>
                                </tr>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($new_opening as $offer)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td><span>#{{ $i }}</span></td>
                                        <td>
                                            <span class="text-nowrap">{{ $offer->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-nowrap">{{ $offer->ar_name }}</span>
                                        </td>

                                        <td><span class="badge light badge-danger">{{ $offer->description }}</span>
                                        </td>
                                        <td><span class="badge light badge-danger">{{ $offer->ar_description }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge light badge-warning">
                                                    {{ $offer->start_date }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($offer->status == 'active')
                                                    <i class="fa fa-circle text-success me-1"></i>
                                                @else
                                                    <i class="fa fa-circle text-danger me-1"></i>
                                                @endif
                                                {{ $offer->status }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-danger light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24"
                                                        version="1.1">
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
                                                        href="{{ route('offers.show', $offer->id) }}">Offer
                                                        Details</a>

                                                    {{-- <a class="dropdown-item edit-offer_n" href="javascript:void(0);"
                                                        class="btn btn-primary mb-1" data-bs-toggle="modal"
                                                        data-id="{{ $offer->id }}"
                                                        data-bs-target="#editMessageModal_n">Edit</a> --}}

                                                    <a class="dropdown-item" href="#"
                                                        onclick="event.preventDefault();
                                                                 document.getElementById('destroy-form-{{ $offer->id }}').submit();">
                                                        Delete
                                                    </a>
                                                    <form id="destroy-form-{{ $offer->id }}"
                                                        action="{{ route('offers.destroy', $offer->id) }}" method="POST"
                                                        style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    @if ($offer->status == 'active')
                                                        <a class="dropdown-item"
                                                            href="{{ route('act_inact__offer', $offer->id) }}"></i>inActive</a>
                                                    @else
                                                        <a class="dropdown-item"
                                                            href="{{ route('act_inact__offer', $offer->id) }}"></i>Active</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Modal -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_employee" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Offer Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('offers.store') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label class="text-black font-w600 form-label">
                                        Old Price
                                        <span class="required">*</span></label>
                                    <input type="number" class="form-control" id="validationCustom01"
                                        value="testname"name="price_old" required="">
                                    @error('price_old')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label class="text-black font-w600 form-label">
                                        New Price
                                        <span class="required">*</span></label>
                                    <input type="number" class="form-control" id="validationCustom01"
                                        value="testname"name="price_new" required="">
                                    @error('price_new')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col col-md-6">
                                    <label class="text-black font-w600 form-label">
                                        Description En
                                        <span class="required">*</span></label>
                                    <textarea row="7"type="text" class="form-control" id="validationCustom01" value="testname"name="description"
                                        required=""></textarea>
                                    @error('description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col col-md-6">
                                    <label class="text-black font-w600 form-label">
                                        Description Ar
                                        <span class="required">*</span></label>
                                    <textarea row="7"type="text" class="form-control" id="validationCustom01" value="testname"name="ar_description"
                                        required=""></textarea>
                                    @error('description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Name En
                                        <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="validationCustom01"
                                        value=""name="name" required="">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                    <label class="text-black font-w600 form-label">
                                        Name Ar
                                        <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="validationCustom01"
                                        value=""name="ar_name" required="">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Services En
                                        <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="validationCustom01"
                                        value=""name="featured" required="">
                                    @error('featured')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Services Ar
                                        <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="validationCustom01"
                                        value=""name="ar_featured" required="">
                                    @error('ar_featured')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Background Image
                                        <span class="required">*</span></label>
                                    <input type="file" name="cover" accept="images/*"
                                        class="form-file-input form-control" required>
                                    @error('cover')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Main Image
                                        <span class="required">*</span></label>
                                    <input type="file" name="main" accept="images/*"
                                        class="form-file-input form-control" required>
                                    @error('main')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Details Images
                                        <span class="required">*</span></label>
                                    <input type="file" name="others[]" accept="images/*"
                                        class="form-file-input form-control" multiple required>
                                    <input type="hidden" name="Restaurant_id" value={{ $res_id }}>
                                    <input type="hidden" name="type" value="offer">
                                    @error('others')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-3 mb-0">
                                    <input type="submit" value="Confirm" class="submit btn btn-primary"
                                        name="submit" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_employee_n" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Opening Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('offers.store') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Start Date
                                        <span class="required">*</span></label>
                                    <input type="date" class="form-control" id="validationCustom01"
                                        value="testname"name="start_date" required="">
                                    @error('start_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Description En
                                        <span class="required">*</span></label>
                                    <textarea row="7"type="text" class="form-control" id="validationCustom01" value="testname"name="description"
                                        required=""></textarea>
                                    @error('description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Description Ar
                                        <span class="required">*</span></label>
                                    <textarea row="7"type="text" class="form-control" id="validationCustom01" value="testname"name="ar_description"
                                        required=""></textarea>
                                    @error('ar_description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Name En
                                        <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="validationCustom01"
                                        value=""name="name" required="">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Name Ar
                                        <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="validationCustom01"
                                        value=""name="ar_name" required="">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Background Image
                                        <span class="required">*</span></label>
                                    <input type="file" name="cover" accept="images/*"
                                        class="form-file-input form-control" required>
                                    @error('cover')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Main Image
                                        <span class="required">*</span></label>
                                    <input type="file" name="main" accept="images/*"
                                        class="form-file-input form-control" required>
                                    @error('main')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">
                                        Details Images
                                        <span class="required">*</span></label>
                                    <input type="file" name="others[]" accept="images/*"
                                        class="form-file-input form-control" multiple required>
                                    <input type="hidden" name="Restaurant_id" value={{ $res_id }}>
                                    <input type="hidden" name="type" value="new_opening">
                                    @error('others')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-3 mb-0">
                                    <input type="submit" value="Confirm" class="submit btn btn-primary"
                                        name="submit" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if ($offers->count() != 0)
        <div class="modal fade" id="editMessageModal">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Offer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('offer_update') }}" id="editOfferForm" autocomplete="off"
                            enctype="multipart/form-data">
                            <input type="text" id="edit_offer_id" name="offer_id">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="text-black font-w600 form-label">
                                            Old Price
                                            <span class="required">*</span></label>
                                        <input type="number" class="form-control" id="validationCustom01"
                                            value="testname"name="price_old" required="">
                                        @error('price_old')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="text-black font-w600 form-label">
                                            New Price
                                            <span class="required">*</span></label>
                                        <input type="number" class="form-control" id="validationCustom01"
                                            value="testname"name="price_new" required="">
                                        @error('price_new')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Description
                                            <span class="required">*</span></label>
                                        <textarea row="7"type="text" class="form-control" id="validationCustom01" value="testname"name="description"
                                            required=""></textarea>
                                        @error('description')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Name
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="validationCustom01"
                                            value=""name="name" required="">
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Services En
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="validationCustom01"
                                            value=""name="featured" required="">
                                        @error('featured')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Services Ar
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="validationCustom01"
                                            value=""name="ar_featured" required="">
                                        @error('ar_featured')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Background Image
                                            <span class="required">*</span></label>
                                        <input type="file" name="cover" accept="images/*"
                                            class="form-file-input form-control">
                                        @error('cover')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Main Image
                                            <span class="required">*</span></label>
                                        <input type="file" name="main" accept="images/*"
                                            class="form-file-input form-control">
                                        @error('main')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Details Images
                                            <span class="required">*</span></label>
                                        <input type="file" name="others[]" accept="images/*"
                                            class="form-file-input form-control" multiple>
                                        <input type="hidden" name="Restaurant_id" value={{ $res_id }}>
                                        <input type="hidden" name="type" value="offer">
                                        @error('others')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="mb-3 mb-0">
                                        <input type="submit" value="Confirm" class="submit btn btn-primary"
                                            name="submit" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade" id="editMessageModal_n">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Offer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('offer_update') }}" id="editOfferForm" autocomplete="off"
                            enctype="multipart/form-data">
                            <input type="text" id="edit_offer_id" name="offer_id">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Start Date
                                            <span class="required">*</span></label>
                                        <input type="date" class="form-control" id="validationCustom01"
                                            value="testname"name="start_date" required="">
                                        @error('start_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Description
                                            <span class="required">*</span></label>
                                        <textarea row="7"type="text" class="form-control" id="validationCustom01" value="testname"name="description"
                                            required=""></textarea>
                                        @error('description')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Name
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="validationCustom01"
                                            value=""name="name" required="">
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Background Image
                                            <span class="required">*</span></label>
                                        <input type="file" name="cover" accept="images/*"
                                            class="form-file-input form-control" required>
                                        @error('cover')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Main Image
                                            <span class="required">*</span></label>
                                        <input type="file" name="main" accept="images/*"
                                            class="form-file-input form-control" required>
                                        @error('main')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">
                                            Details Images
                                            <span class="required">*</span></label>
                                        <input type="file" name="others[]" accept="images/*"
                                            class="form-file-input form-control" multiple required>
                                        <input type="hidden" name="Restaurant_id" value={{ $res_id }}>
                                        <input type="hidden" name="type" value="new_opening">
                                        @error('others')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="mb-3 mb-0">
                                        <input type="submit" value="Confirm" class="submit btn btn-primary"
                                            name="submit" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endif
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($(".alert-danger").length) {
                $("#add_employee").modal('show');
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.edit-offer').click(function() {
                var offerId = $(this).data('id');
                $('#edit_offer_id').val(offerId);
            });
        });
        $(document).ready(function() {
            $('.edit-offer_n').click(function() {
                var offerId = $(this).data('id');
                // تحديث ID الفئة في الحقل المخفي
                $('#edit_offer_id').val(offerId);
            });
        });
    </script>

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