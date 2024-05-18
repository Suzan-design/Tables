<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="dashboard_bar">
                        Dashboard
                    </div>
                </div>

                <ul id="app"class="navbar-nav header-right">
                    
                    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
                    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}" /> --}}
                    {{-- @if (Auth::user()->role_name == 'staff')
                        <li class="nav-item">
                            <a href="{{ route('reservations_generate_get',Restaurant::where('user_id',Auth::user()->id)) }}"
                                class="btn btn-primary d-sm-inline-block d-none" alt="Transparent MDB Logo"
                                id="animated-img1">
                                Reservations Management
                                <i class="las la-signal ms-3 scale5"></i></a>
                        </li>
                        @endif --}}
                   
                    

                </ul>
            </div>
        </nav>

    </div>
</div>
{{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}

{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
{{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}" /> --}}
{{-- <script defer src="{{ mix('js/app.js') }}"></script> --}}
<!-- Modal Add Category -->
{{-- <div class="modal fade none-border" id="generate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Reservations Generate</strong></h4>
            </div>

            <div class="modal-body">
                <form method="post" action="{{ route('reservations_generate') }}" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label form-label">work start</label>
                            <input class="form-control form-white" type="time" name="start" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label form-label">work end</label>
                            <input class="form-control form-white" type="time" name="end" required>
                        </div>
                    </div>

                    <p>note - Depending on the start and end time, bookings will be scheduled every half hour</p>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit"class="btn btn-danger">Confirm</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div> --}}
