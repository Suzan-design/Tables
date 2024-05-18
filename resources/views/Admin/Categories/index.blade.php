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
    Restaurants Management - Categories
@stop
@section('content')
    <div class="container-fluid">
        <div class="d-flex mb-3">
            <div class="mb-3 align-items-center me-auto">
                <h4 class="card-title"></h4>
            </div>


            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">
                <i class="fa fa-calendar me-3 scale3">
                </i>Add Category
            </a>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive table-container fs-14">
                    <table class="table table-fixed-height card-table display mb-4 dataTablesCard " id="example5">
                            <tr>
                                <th>ID</th>
                                <th>Name en</th>
                                <th>Name ar</th>
                                <th>Icon</th>
                                <th>Description en</th>
                                <th>Description ar</th>
                                <th>number of restaurants</th>
                                <th>Actions</th>
                            </tr>
                            @forelse($categories as $category)
                                <tr>
                                    <td><span class="text-black font-w500">#{{ $category->id }}</span></td>
                                    <td><span class="btn btn-danger light">{{ $category->name }}</span></td>
                                    <td><span class="btn btn-danger light">{{ $category->ar_name }}</span></td>
                                    <td>
                                        <img style="height:16px;width:16px;"src="{{ asset($category->icon) }}"
                                            alt="{{ $category->name }}">
                                    </td>
                                    <td><span class="btn btn-warning light">{{ $category->description }}</span></td>
                                    <td><span class="btn btn-warning light">{{ $category->ar_description }}</span></td>
                                    <td><span class="btn btn-success light">{{ $category->Restaurants->count() }}</span>
                                    </td>
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
                                                    onclick="event.preventDefault();
                                             document.getElementById('destroy-form-{{ $category->id }}').submit();">
                                                    Delete
                                                </a>
                                                <form id="destroy-form-{{ $category->id }}"
                                                    action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                    style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>


                                                <a class="dropdown-item edit" href="javascript:void(0);"
                                                    class="btn btn-primary mb-1" data-bs-toggle="modal"
                                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                    data-description="{{ $category->description }}" data-icon="{{ asset($category->icon) }}" data-ar_description="{{ $category->ar_description }}" data-ar_name="{{ $category->ar_name }}"
                                                    data-bs-target="#edit">Edit</a>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="{{ route('categories.store') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add a Category</h5>
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
                                            placeholder="name" required />
                                        @error('name', 'add')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                        <label class="text-black font-w600 form-label">Name Ar
                                            <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="ar_name"
                                            placeholder="arabic name"required />
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
                                        <label class="text-black font-w600 form-label">Description En</label>
                                        <textarea rows="8" class="form-control" name="description" placeholder="description"required>
                                            @error('description', 'add')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </textarea>
                                    </div>
                                </div>
<div class="col-lg-12">

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="text-black font-w600 form-label">Description Ar</label>
                                        <textarea rows="8" class="form-control" name="ar_description" placeholder="description"required>
                                            @error('description', 'add')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </textarea>
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

    @if ($categories->count() != 0)
        <div class="modal fade" id="edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="post" action="{{ route('category_update') }}" id="edit" autocomplete="off"
                    enctype="multipart/form-data">
                    <input type="hidden" id="edit_category_id" name="category_id">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form class="comment-form">
                                <div class="row">
                                    <div class="col-lg-12">

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Name En
                                                <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="name"required />
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
                                                placeholder="name"required />
                                            @error('ar_name', 'edit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                <div class="mb-3">
                                    <img id="existing_icon_preview" src="" alt="Existing Icon" width="100" />
                                </div>
                            </div>

                            <!-- Icon Upload -->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">New Icon</label>
                                    <input type="file" name="icon" class="form-control" name="icon" placeholder="Upload new icon" />
                                    @error('icon', 'edit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Description En</label>
                                            <textarea rows="8" class="form-control" name="description" placeholder="description"required>
                                        </textarea>
                                            @error('description', 'edit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="text-black font-w600 form-label">Description Ar</label>
                                            <textarea rows="8" class="form-control" name="ar_description" placeholder="description"required>
                                        </textarea>
                                            @error('ar_description', 'edit')
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
                <form method="post" action="{{ route('categories.destroy', $category->id) }}" autocomplete="off"
                    enctype="multipart/form-data">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Cuisine</h5>
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
                $('#edit .text-danger').remove(); // إزالة رسائل الخطأ

                var Id = $(this).data('id');
                var name = $(this).data('name');
                var description = $(this).data('description');
                var ar_name = $(this).data('ar_name');
                var ar_description = $(this).data('ar_description');
                var iconUrl = $(this).data('icon'); // Retrieve the icon URL
                console.log(ar_name);
                //$('#edit_id').val(userId);
                 var categoryId = $(this).data('id');
                 console.log(categoryId);
                $('#edit_category_id').val(categoryId);
                $('#edit input[name="name"]').val(name);
                $('#edit textarea[name="description"]').val(description);
                $('#edit input[name="ar_name"]').val(ar_name);
                $('#edit textarea[name="ar_description"]').val(ar_description);
 $('#existing_icon_preview').attr('src', iconUrl);
                var updateUrl = "{{ url('/categories') }}/" + Id; // Update this URL based on your routing
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
            $('.edit').click(function() {
                var categoryId = $(this).data('id');
                console.log(categoryId);
                $('#edit_category_id').val(categoryId);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-category').click(function() {
                var categoryId = $(this).data('id');
                $('#delete_category_id').val(categoryId);
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