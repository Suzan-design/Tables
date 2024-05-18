@extends('layouts.master')

@section('title')
    Notification Dashboard
@endsection

@section('content')
    <hr />
    <div class="beneficiaries-info">
        <span class="beneficiaries-label">Beneficiaries of this Notification</span>
    </div>

    <form action="{{ route('sent_notification') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="form-group">
            <label for="code">Title:</label>
            <input type="text" class="form-control" id="title" name="title" >
        </div>
        <div class="form-group">
            <label for="code">description:</label>
            <input type="text" class="form-control" id="title" name="description" >
        </div>

        <hr />
        <h4>User Level</h4>
        <div class="form-group">
        <label for="city">City:</label>
        <input type="text" class="form-control" id="display_city" placeholder="Select cities">
        <div id="select_container" style="display:none;">
            <select class="form-control" id="user_city" name="user_city[]" multiple>
                  <option value="Aleppo">Aleppo</option>
                  <option value="Al-hasakah">Al-Ḥasakah</option>
                  <option value="Al-Qamishli">Al-Qamishli</option>
                  <option value="Quneitra">Al-Qunayṭirah</option>
                  <option value="Raqqa">Raqqa</option>
                  <option value="As-suwayda">Al-Suwayda</option>
                  <option value="Damascus">Damascus</option>
                  <option value="Daraa">Daraa</option>
                  <option value="Deir ez-zor">Deir ez-zor</option>
                  <option value="Ḥama">Ḥama</option>
                  <option value="Homs">Homs</option>
                  <option value="Idlib">Idlib</option>
                  <option value="Latakia">Latakia</option>
                  <option value="Rif Dimashq">Rif Dimashq</option>
                  <option value="Tartus">Tartus</option>
              </select>
          </div>
        </div>

        <hr />

        <div class="form-group">
            <label for="ageRange">Age Range:</label>
            <input type="range" class="form-control-range" id="ageRangeStart" name="ageRangeStart" min="0" max="100" value="18">
            <input type="range" class="form-control-range" id="ageRangeEnd" name="ageRangeEnd" min="0" max="100" value="60">
            <p>Selected Age Range: <span id="ageRangeDisplay">18 - 60</span> years</p>
        </div>

        <div class="form-group">
            <label for="bookingRange">Booking Range:</label>
            <input type="range" class="form-control-range" id="bookingRangeStart" name="bookingRangeStart" min="0" max="1000" value="18">
            <input type="range" class="form-control-range" id="bookingRangeEnd" name="bookingRangeEnd" min="0" max="1000" value="60">
            <p>Selected booking Range: <span id="bookingRangeDisplay">18 - 60</span> booking</p>
        </div>

        <hr />

        <button type="submit" style="width:250px;" class="btn btn-primary">Submit</button>
    </form>

    <!-- Initialize Select2 -->
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $('#user_city').select2();
        });
        document.getElementById('display_city').addEventListener('click', function(event) {
        
        event.stopPropagation();
        var container = document.getElementById('select_container');
        container.style.display = container.style.display === 'none' ? 'block' : 'none';
        });
        
        document.getElementById('user_city').addEventListener('change', function(event) {
        
            updateTextInput();
        });
        
        document.addEventListener('click', function(e) {
        
            var container = document.getElementById('select_container');
            if (!container.contains(e.target)) {
                container.style.display = 'none';
            }
        });
        
        // Update the text input with selected options
        function updateTextInput() {
          
            var selectedOptions = document.getElementById('user_city').selectedOptions;
            var displayValues = Array.from(selectedOptions).map(option => option.text);
            document.getElementById('display_city').value = displayValues.join(', ');
        }
        
        // Listener to handle removal of choices directly from the text input
        document.getElementById('display_city').addEventListener('input', function(e) {
          
            var inputValues = e.target.value.split(',').map(value => value.trim());
            var options = document.getElementById('user_city').options;
            for (var i = 0; i < options.length; i++) {
                if (inputValues.includes(options[i].text)) {
                    options[i].selected = true;
                } else {
                    options[i].selected = false;
                }
            }
        });

    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('multivalueInput');
            const container = document.getElementById('multivalueContainer');

            input.addEventListener('keyup', function (e) {
                if (e.key === ' ') {
                    const value = input.value.trim().replace(/,$/, '');
                    if (value) {
                        addTag(value);
                    }
                    input.value = '';
                }
            });

            function addTag(value) {
                // Create the visible tag
                const tag = document.createElement('span');
                tag.className = 'tag';
                tag.textContent = value;
                container.appendChild(tag);

                // Create a hidden input for the form
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'events_id[]';
                hiddenInput.value = value;
                container.appendChild(hiddenInput);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ageRangeStart = document.getElementById('ageRangeStart');
            const ageRangeEnd = document.getElementById('ageRangeEnd');
            const ageRangeDisplay = document.getElementById('ageRangeDisplay');

            function updateAgeDisplay() {
                ageRangeDisplay.textContent = `${ageRangeStart.value} - ${ageRangeEnd.value}`;
            }

            ageRangeStart.addEventListener('input', updateAgeDisplay);
            ageRangeEnd.addEventListener('input', updateAgeDisplay);

            updateAgeDisplay(); // Initialize display
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bookingRangeStart = document.getElementById('bookingRangeStart');
            const bookingRangeEnd = document.getElementById('bookingRangeEnd');
            const bookingRangeDisplay = document.getElementById('bookingRangeDisplay');

            function updateAgeDisplay() {
                bookingRangeDisplay.textContent = `${bookingRangeStart.value} - ${bookingRangeEnd.value}`;
            }

            bookingRangeStart.addEventListener('input', updateAgeDisplay);
            bookingRangeEnd.addEventListener('input', updateAgeDisplay);

            updateAgeDisplay(); // Initialize display
        });
    </script>
@endsection

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #select_container {
            position: absolute;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ccc;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            height: auto;
        }

        .btn-primary {
            background-color: #5cb85c;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
        }
        .content-wrapper {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            gap: 20px;
        }


        .beneficiaries-info {
            position: -webkit-sticky;
            position: sticky;
            top: 5px;
            flex-basis: 45%;
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #e7f5ff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .promo-form {
            flex-basis: 45%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        select[multiple] {
            height: auto;
            padding: 10px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        select[multiple] option:hover {
            background-color: #f0f0f0;
        }

        #multivalueContainer {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 40px;
            margin-top: 5px;
        }

        .tag {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 3px;
            margin-top: 5px;
        }
        .form-control-range {
            width: 100%; /* Full-width */
            margin: 15px 0; /* Add some margin */
        }
        .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: 0;
        }
    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection


