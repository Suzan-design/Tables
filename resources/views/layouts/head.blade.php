<title>@yield("title")</title>
<!-- Favicon -->
<link rel="shortcut icon" href="{{ URL::asset('dashboard/images/logo.jpg') }}" type="image/png" />
@yield('css')
<!--- Style css -->
<link href="{{ URL::asset('dashboard/css/style.css') }}" rel="stylesheet">


<link href="{{ URL::asset('dashboard/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="{{ URL::asset('dashboard/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
<link href="{{ URL::asset('dashboard/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
<!--- Style css
@if (App::getLocale() == 'en')
    <link href="{{ URL::asset('assets/css/ltr.css') }}" rel="stylesheet">
@else
    <link href="{{ URL::asset('assets/css/rtl.css') }}" rel="stylesheet">
@endif
 -->
