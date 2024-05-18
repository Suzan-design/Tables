<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Restaurants Management - Work times</title>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        body {
            color: #fff;
            overflow-x: hidden;
            height: 100%;
            background-color: #5acec4;
            background-repeat: no-repeat
        }

        .container-fluid {
            margin-top: 80px;
            margin-bottom: 80px
        }

        .card {
            background-color: #424242;
            padding: 40px 10px
        }

        .text-grey {
            color: #9E9E9E
        }

        .fa {
            font-size: 25px;
            cursor: pointer
        }

        input,
        select {
            padding: 2px 6px;
            border: none;
            border-bottom: 1px solid #fff;
            border-radius: none;
            box-sizing: border-box;
            color: #fff;
            background-color: transparent;
            font-size: 14px;
            letter-spacing: 1px;
            text-align: center !important
        }

        input:focus,
        select:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border-bottom: 1px solid #00C853;
            outline-width: 0
        }

        select option {
            background-color: #616161
        }

        select option:focus {
            background-color: #00C853 !important
        }

        ::placeholder {
            color: #fff;
            opacity: 1
        }

        :-ms-input-placeholder {
            color: #fff
        }

        ::-ms-input-placeholder {
            color: #fff
        }

        button:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            outline-width: 0
        }

        .btn {
            border-radius: 50px;
            width: 120px
        }

        .exit {
            border: 1px solid #9E9E9E;
            color: #9E9E9E;
            background-color: transparent
        }

        .exit:hover {
            border: 1px solid #9E9E9E;
            color: #000 !important;
            background-color: #9E9E9E
        }

        @media screen and (max-width: 768px) {
            .mob {
                width: 70%
            }

            select.mob {
                width: 50%
            }
        }
    </style>
</head>

<body className='snippet-body'>
    <div class="container-fluid px-1 px-sm-4 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-9 col-xl-8">
                <form method="post" action="{{ route('reservations_generate_post') ?? '-' }}" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0">
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">
                                    <option value="opt2">Sat.</option>
                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time" value="{{ $times->sat_from ?? '14:00' }}"
                                            name="sat_from"> </div>


                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time" value="{{ $times->sat_to ?? '23:00' }}"
                                            name="sat_to">

                                    </div>
                                    <input type="checkbox" id="sat_closed" name="sat_closed">
                                    <label for="sat_closed">Closed</label>


                                    <div class="text-danger">
                                        @error('sat_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">

                                    <option value="opt2">Sund.</option>

                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time" value="{{ $times->sun_from ?? '14:00' }}"
                                            name="sun_from"> </div>
                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time" value="{{ $times->sun_to ?? '23:00' }}"
                                            name="sun_to"> </div>
                                    <input type="checkbox" name="sun_closed">
                                    <label for="mon_closed">Closed</label>
                                    <div class="text-danger">
                                        @error('sun_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">

                                    <option value="opt1">Mon.</option>

                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time" value="{{ $times->mon_from ?? '14:00' }}"
                                            name="mon_from"> </div>
                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time"value="{{ $times->mon_to ?? '23:00' }}"
                                            name="mon_to"> </div>
                                    <input type="checkbox" name="mon_closed">
                                    <label for="mon_closed">Closed</label>
                                    <div class="text-danger">
                                        @error('mon_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">
                                    <option value="opt2">Tue.</option>
                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time"value="{{ $times->tue_from ?? '14:00' }}"
                                            name="tue_from"> </div>
                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time" value="{{ $times->tue_to ?? '23:00' }}"
                                            name="tue_to"> </div>
                                    <input type="checkbox" name="tue_closed">
                                    <label for="mon_closed">Closed</label>
                                    <div class="text-danger">
                                        @error('tue_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">
                                    <option value="opt1">Wed.</option>
                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time" value="{{ $times->wed_from ?? '14:00' }}"
                                            name="wed_from"> </div>
                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time" value="{{ $times->wed_to ?? '23:00' }}"
                                            name="wed_to"> </div>
                                    <input type="hidden" value="{{ $id }}" name="res_id">
                                    <input type="checkbox" name="wed_closed">
                                    <label for="mon_closed">Closed</label>
                                    <div class="text-danger">
                                        @error('wed_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">
                                    <option value="opt2">Thu.</option>
                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time" value="{{ $times->thu_from ?? '14:00' }}"
                                            name="thu_from"> </div>
                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time" value="{{ $times->thu_to ?? '23:00' }}"
                                            name="thu_to"> </div>
                                    <input type="checkbox" name="thu_closed">
                                    <label for="mon_closed">Closed</label>
                                    <div class="text-danger">
                                        @error('thu_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3">
                            <div class="col-sm-2"> <label class="text-grey mt-1 mb-3">Open Hours</label> </div>
                            <div class="col-sm-10 list">
                                <div class="mb-2 row justify-content-between px-3">
                                    <option value="opt1">Fri.</option>
                                    <div class="mob"> <label class="text-grey mr-1">From</label> <input
                                            class="ml-1" type="time" value="{{ $times->fri_from ?? '14:00' }}"
                                            name="fri_from"> </div>
                                    <div class="mob mb-2"> <label class="text-grey mr-4">To</label> <input
                                            class="ml-1" type="time" value="{{ $times->fri_to ?? '23:00' }}"
                                            name="fri_to"> </div>
                                    <input type="checkbox" name="fri_closed">
                                    <label for="mon_closed">Closed</label>
                                    <div class="text-danger">
                                        @error('fri_to')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-3 mt-3 justify-content-center">
                            <button type="submit" name="action"value="submit"
                                class="btn btn-success ml-2">Done</button>
                            <button type="button" onclick="handleCancel()" name="action" value="cancel"
                                class="btn exit ml-2">Cancel</button>
                        </div>
                        @php
                            $redirectRoute = auth()->user()->roleName == 'staff' ? route('my_records') : route('Restaurants.index');
                        @endphp
                        
                        <script>
                         function handleCancel() {
                            window.location.href = "{{ $redirectRoute }}";
                            }
                        </script>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
    </script>
    <script type='text/javascript' src='#'></script>
    <script type='text/javascript' src='#'></script>
    <script type='text/javascript' src='#'></script>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('.add').click(function() {
                $(".list").append(
                    '<div class="mb-2 row justify-content-between px-3">' +
                    '<select class="mob mb-2">' +
                    '<option value="opt1">Mon-Fri</option>' +
                    '<option value="opt2">Sat-Sun</option>' +
                    '</select>' +
                    '<div class="mob">' +
                    '<label class="text-grey mr-1">From</label>' +
                    '<input class="ml-1" type="time" name="from">' +
                    '</div>' +
                    '<div class="mob mb-2">' +
                    '<label class="text-grey mr-4">To</label>' +
                    '<input class="ml-1" type="time" name="to">' +
                    '</div>' +
                    '<div class="text-danger">' +
                    '</div>' +
                    '</div>');
            });

            $(".list").on('click', '.cancel', function() {
                $(this).parent().remove();
            });

        });
    </script>
    <script type='text/javascript'>
        var myLink = document.querySelector('a[href="#"]');
        myLink.addEventListener('click', function(e) {
            e.preventDefault();
        });
    </script>

</body>

</html>
