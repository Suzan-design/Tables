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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- row -->
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center mb-3">

            <a href="javascript:void()" data-bs-toggle="modal"
                data-bs-target="#add_employee"class="btn btn-outline-primary mb-3"><i
                    class="fa fa-add me-3 scale3"></i>Manager Add</a>
        </div>
        <div class="row">
            <div class="col-xl-12 tab-content">
                <div class="tab-pane fade show active" id="Restaurants" role="tabpanel" aria-labelledby="Restaurants-tab">
                    <div class="table-responsive table-container fs-14">
                        <table class="table card-table display mb-4 dataTablesCard text-black" id="example5">
                            <thead>
                                <tr>



                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($data as $key => $user)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td><span>#{{ $i }}</span></td>
                                        <td>
                                            <span class="text-nowrap">{{ $user->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-nowrap">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            <span class="text-nowrap">{{ $user->roleName }}</span>
                                        </td>
                                        <td>
                                            <span class="text-nowrap">{{ $user->phone }}</span>
                                        </td>
                                        {{-- <td>
                                            <span class="text-nowrap">{{ $user->status }}</span>
                                        </td> --}}


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
                                                    <a class="dropdown-item" href=""></i>edit</a>

                                                    <a class="dropdown-item" href="#"
                                                        onclick="event.preventDefault();
                                                             document.getElementById('destroy-form-{{ $user->id }}').submit();">
                                                        Delete
                                                    </a>
                                                    <form id="destroy-form-{{ $user->id }}"
                                                        action="{{ route('users.destroy', $user->id) }}" method="POST"
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
                                                @endif  --}}
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
    </div>
    <!-- Modal -->
    <div class="modal fade" id="add_employee" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                        action="{{ route('users.store', 'test') }}" method="post">
                        {{ csrf_field() }}
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
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">Email
                                        <span class="required">*</span></label>
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">Phone
                                        <span class="required">*</span></label>
                                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                    @error('phone')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">password
                                        <span class="required">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20"
                                        data-parsley-class-handler="#lnWrapper" name="password" required=""
                                        type="password">
                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">password Confirm
                                        <span class="required">*</span></label>
                                    <input class="form-control form-control-sm mg-b-20"
                                        data-parsley-class-handler="#lnWrapper" name="confirm-password" required=""
                                        type="password">
                                    @error('confirm-password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>  
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="text-black font-w600 form-label">المنصب
                                        <span class="required">*</span></label>
                                       
                                    {!! Form::select('', $roles, ['class' => 'default-select wide form-control', 'multiple']) !!}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3 mb-0">
                                    <input type="submit" value="Confirm" class="submit btn btn-primary"
                                        name="submit" />
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
