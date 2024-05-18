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
    Restaurants Management - MenuItems
@stop
@section('content')
    <div class="container-fluid">
        <div class="d-flex mb-3">
            <div class="mb-3 align-items-center me-auto">
                <h4 class="card-title"></h4>
            </div>


            <a href="javascript:void(0);" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#add">
                <i class="fa fa-calendar me-3 scale3">
                </i>Add a Item
            </a>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive table-container fs-14">
                    <table class="table card-table display mb-4 dataTablesCard " id="example5">
                        <thead>
                            <tr>
                                <th>ID Item</th>
                                <th>Name en</th>
                                <th>Name ar</th>
                                <th>Icon</th>
                                <th>Type en</th>
                                <th>Type ar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td><span class="text-black font-w500">#{{ $item->id }}</span></td>
                                    <td><span class="btn btn-danger light">{{ $item->name }}</span></td>
                                    <td><span class="btn btn-danger light">{{ $item->ar_name }}</span></td>
                                    <td>
                                        <img style="height:16px;width:16px;"src="{{ asset($item->icon) }}"
                                            alt="{{ $item->name }}">
                                    </td>
                                    <td><span class="btn btn-warning light">{{ $item->type->name }}</span></td>
                                    <td><span class="btn btn-warning light">{{ $item->type->ar_name }}</span></td>
                                    <td>
                                        <div class="dropdown">
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
                                            <div class="dropdown-menu dropdown-menu-right">

                                                <a class="dropdown-item" href="#"
                                                    onclick="event.preventDefault();   document.getElementById('destroy-form-{{ $item->id }}').submit();">
                                                    Delete
                                                </a>
                                                <form id="destroy-form-{{ $item->id }}"
                                                    action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                                <a class="dropdown-item edit" href="javascript:void(0);"
                                                    class="btn btn-primary mb-1" data-bs-toggle="modal"
                                                    data-id="{{ $item->id }}" data-ar_name="{{$item->ar_name}}" data-name="{{ $item->name }}"  data-type="{{ $item->type->id }}"
                                                    data-bs-target="#edit">Edit</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="{{ route('items.store') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form class="comment-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">Name En
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="name"required />
                                        <input type="hidden" class="form-control" name="restaurant_id"
                                            value={{ $restaurant_id }}>
                                        @error('name', 'add')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">Name Ar
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="ar_name"
                                            placeholder="name"required />
                                        @error('name', 'add')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
 
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">Icon</label>
                                        <input type="file" class="form-control" name="icon" placeholder="icon"
                                            required />
                                        @error('icon', 'add')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">Type</label>
                                        <select class="default-select wide form-control" name="type_id"
                                            id="validationCustom05">

                                            <option data-display="Select">Please select</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_id', 'add')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3 mb-0">
                                        <input type="submit" value="submit" class="submit btn btn-primary"
                                            name="submit" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if ($items->count() != 0)
        <div class="modal fade" id="edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="post" action="{{ route('item_update') }}" id="edit" autocomplete="off"
                    enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="item_id">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form class="comment-form">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Name En
                                                <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="name"required />
                                            <input type="hidden" class="form-control" name="restaurant_id"
                                                value={{ $restaurant_id }}>
                                            @error('name', 'edit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Name Ar
                                                <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="ar_name"
                                                placeholder="name" required />
                                            @error('ar_name', 'edit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Icon</label>
                                            <input type="file" class="form-control" name="icon" placeholder="icon"
                                                 />
                                            @error('icon', 'edit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Type</label>

                                            <select class="default-select wide form-control" name="type_id" id="type_select">
                                                <option data-display="Select">Please select</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('type', 'edit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3 mb-0">
                                            <input type="submit" value="submit" class="submit btn btn-primary"
                                                name="submit" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="deleteMessageModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="post" action="{{ route('items.destroy', $item->id) }}" autocomplete="off"
                    enctype="multipart/form-data">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form class="comment-form">
                                <div class="row">
                                    <div class="swal2-header">
                                        <ul class="swal2-progresssteps" style="display: none;"></ul>
                                        <div class="swal2-icon swal2-error" style="display: none;"><span
                                                class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span
                                                    class="swal2-x-mark-line-right"></span></span></div>
                                        <div class="swal2-icon swal2-question" style="display: none;"><span
                                                class="swal2-icon-text">?</span></div>
                                        <div class="swal2-icon swal2-warning swal2-animate-warning-icon"
                                            style="display: flex;"><span class="swal2-icon-text"></span></div>
                                        <div class="swal2-icon swal2-info" style="display: none;"><span
                                                class="swal2-icon-text">i</span></div>
                                        <div class="swal2-icon swal2-success" style="display: none;">
                                            <div class="swal2-success-circular-line-left"
                                                style="background-color: rgb(255, 255, 255);"></div><span
                                                class="swal2-success-line-tip"></span> <span
                                                class="swal2-success-line-long"></span>
                                            <div class="swal2-success-ring"></div>
                                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);">
                                            </div>
                                            <div class="swal2-success-circular-line-right"
                                                style="background-color: rgb(255, 255, 255);"></div>
                                        </div><img class="swal2-image" style="display: none;">
                                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure to
                                            delete ?</h2><button type="button" class="swal2-close"
                                            style="display: none;">×</button>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3 mb-0">
                                            <input type="submit" value="submit" class="submit btn btn-primary"
                                                name="submit" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            @if ($errors->hasBag('add'))
                $('#add').modal('show');
            @endif
            @if ($errors->hasBag('edit'))
                $('#edit').modal('show');
            @endif
        });
    </script>

    <script>
        $(document).ready(function() {
            @if (session('edit_id') && $errors->any())
                $('#edit').modal('show');
            @endif
            $('.edit').click(function() {
                $('#edit .text-danger').remove();
                var Id = $(this).data('id');
                var name = $(this).data('name');
                var ar_name = $(this).data('ar_name');
                var type = $(this).data('type');

                $('#edit_id').val(Id);
                $('#edit input[name="name"]').val(name);
                $('#edit input[name="ar_name"]').val(ar_name);

                $('#edit select[name="type_id"]').val(type);
                $('#type_select').val(type);


                var updateUrl = "{{ url('/items') }}/" + Id;
                $('#edit').attr('action', updateUrl);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            @if (session('add_error') && $errors->isNotEmpty())
                $('#add').modal('show');
            @endif
            @if (session('edit_id') && $errors->isNotEmpty())
                $('#edit').modal('show');
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            @if (session('edit_error') && $errors->isNotEmpty())
                $('#edit').modal('show');
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {

            $('.add').click(function() {
                var itemId = $(this).data('id');
                $('#edit_item_id').val(itemId);
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('.delete-item').click(function() {
                var itemId = $(this).data('id');
                $('#delete_item_id').val(itemId);
            });
        });
    </script>
@endsection
@section('js')
@endsection
