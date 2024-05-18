@extends('layouts.master')
@section('css')
@endsection
@section('title')
    Restaurants Management - Customers
@stop
@section('content')
    @if (session()->has('Add'))
        <script>
            window.onload = function() {
                notif({
                    msg: " تم اضافة الصلاحية بنجاح",
                    type: "success"
                });
            }
        </script>
    @endif

    @if (session()->has('edit'))
        <script>
            window.onload = function() {
                notif({
                    msg: " تم تحديث بيانات الصلاحية بنجاح",
                    type: "success"
                });
            }
        </script>
    @endif

    @if (session()->has('delete'))
        <script>
            window.onload = function() {
                notif({
                    msg: " تم حذف الصلاحية بنجاح",
                    type: "error"
                });
            }
        </script>
    @endif
    <!-- row -->
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center mb-3">
            <div class="mb-3 me-auto">
                <div class="card-tabs style-1 mt-3 mt-sm-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" data-bs-toggle="tab" id="transaction-tab"
                                data-bs-target="#Restaurants" role="tab">Roles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-bs-toggle="tab" id="Completed-tab"
                                data-bs-target="#Actived" role="tab">Permissions</a>
                        </li>
                    </ul>
                </div>
            </div>
            <a href="javascript:void()" data-bs-toggle="modal"
                data-bs-target="#add_employee"class="btn btn-outline-primary mb-3"><i class="fa fa-add me-3 scale3"></i>Role
                Add</a>
        </div>
        <div class="row">
            <div class="col-xl-12 tab-content">
                <div class="tab-pane fade show active" id="Restaurants" role="tabpanel" aria-labelledby="Restaurants-tab">
                    <div class="table-responsive fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example5">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($roles as $key => $role)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td><span>#{{ $i }}</span></td>
                                        <td>
                                            <span class="text-nowrap">{{ $role->name }}</span>
                                        </td>
                                        <td><span class="badge light badge-danger">
                                                <ul>
                                                    @if (!empty($role->permissions))
                                                        @foreach ($role->permissions as $v)
                                                            <li>{{ $v->name }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </span>
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
                                                        href="{{ route('roles.edit', $role->id) }}"></i>edit</a>

                                                    <a class="dropdown-item" href="#"
                                                        onclick="event.preventDefault();
                                                             document.getElementById('destroy-form-{{ $role->id }}').submit();">
                                                        Delete
                                                    </a>
                                                    <form id="destroy-form-{{ $role->id }}"
                                                        action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                        style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    {{-- @if ($Restaurant->staff->status == 'active')
                                                    <a class="dropdown-item"
                                                        href="{{ route('act_inact__Restaurant', $Restaurant->id) }}"></i>inActive</a>
                                                @else
                                                    <a class="dropdown-item"
                                                        href="{{ route('act_inact__Restaurant', $Restaurant->id) }}"></i>Active</a>
                                                @endif --}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="Actived" role="tabpanel" aria-labelledby="Actived-tab">
                    <div class="table-responsive fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example6">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($permission as $permissio)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td><span>#{{ $i }}</span></td>
                                    <td>
                                        <span class="text-nowrap">{{ $permissio->name }}</span>
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
    <!-- Modal -->
    <div class="modal fade" id="add_employee" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="text-black font-w600 form-label">Name
                                    <span class="required">*</span></label>
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="text-black font-w600 form-label">الصلاحيات
                                    <span class="required">*</span></label>
                                <ul id="treeview1">

                                    @foreach ($permission as $value)
                                        <label
                                            style="font-size: 16px;">{{ Form::checkbox('permissions[]', $value->id, false, ['class' => 'name']) }}
                                            {{ $value->name }}</label>
                                    @endforeach
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3 mb-0">
                                <input type="submit" value="Confirm" class="submit btn btn-primary" name="submit" />
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($(".alert-danger").length) {
                $("#add_employee").modal('show');
            }
        });
    </script>

@endsection
@section('js')
@endsection
