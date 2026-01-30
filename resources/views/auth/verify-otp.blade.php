<!DOCTYPE html>
<html lang="en">
<head>
<!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- site icon -->
    <link rel="icon" href="{{ url('images/favicon.ico') }}" type="image/ico" />
    <!-- PNG favicon for modern browsers -->
    <link rel="icon" href="{{ url('images/favicon.png') }}" type="image/png" />
    <link rel="apple-touch-icon" href="{{ url('images/apple-touch-icon.png') }}">
    <link rel="icon" sizes="192x192" href="{{ url('images/android-icon-192x192.png') }}">
    <link rel="icon" sizes="16x16" href="{{ url('images/favicon-16x16.png') }}">
    <link rel="icon" sizes="32x32" href="{{ url('images/favicon-32x32.png') }}">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}" />
    <!-- site css -->
    <link rel="stylesheet" href="{{ url('style.css') }}" />
    <!-- responsive css -->
    <link rel="stylesheet" href="{{ url('css/responsive.css') }}" />
    <!-- color css -->
    <!-- <link rel="stylesheet" href="{{ url('css/color.css') }}" /> commented because not found -->
    <!-- select bootstrap --> 
    <link rel="stylesheet" href="{{ url('css/bootstrap-select.css') }}" />
    <!-- scrollbar css -->
    <link rel="stylesheet" href="{{ url('css/perfect-scrollbar.css') }}" />
    <!-- custom css -->
    <link rel="stylesheet" href="{{ url('css/custom.css') }}" />
    <!-- [if lt IE 9]> -->
    <!-- <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> -->
    <!-- <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script> -->
    <!-- <![endif] -->

    <!-- Styles / Scripts -->
    @vite(['resources/js/app.js','resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>OTP Verification</title>
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow p-4">
        <h4 class="mb-3 text-center">OTP Verification</h4>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        
          @if(session('otp_expired'))
            <div class="alert alert-warning">The OTP has expired. Please log in again.</div>
        @endif

        <form method="POST" action="{{ route('verify.otp') }}">
            @csrf
            <div class="form-group">
                <label for="otp">Enter OTP sent to Admin:</label>
                <input type="text" id="otp" name="otp" class="form-control" maxlength="6" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-3">Verify</button>
        </form>
         <a href="{{ route('login') }}" class="btn btn-danger btn-block mt-3">Try LOGIN Again</a>
    </div>
</div>

       
<script src="{{ url('js/jquery.min.js') }}"></script>
<script src="{{ url('js/popper.min.js') }}"></script>
<script src="{{ url('js/bootstrap.min.js') }}"></script>
<!-- wow animation -->
<script src="{{ url('js/animate.js') }}"></script>
<!-- select country -->
<script src="{{ url('js/bootstrap-select.js') }}"></script>
<script src="{{ url('js/sweetalert2@11.js') }}"></script>

<!-- owl carousel -->
<script src="{{ url('js/owl.carousel.js') }}"></script>

<script src="{{ url('js/perfect-scrollbar.min.js') }}"></script>
<script>
    var ps = new PerfectScrollbar('#sidebar');

    $('.alpha-only').bind('keyup blur', function() {
        $(this).val($(this).val().replace(/[^A-Za-z_\s]/, ''));
    });
    $('.number-only').bind('keyup blur', function() {
        $(this).val($(this).val().replace(/[^0-9\+\s\-\()]/, ''));
    });
</script>
<!-- custom js -->
<script src="{{ url('js/custom.js') }}"></script>
<!-- <script src="{{ url('js/chart_custom_style1.js') }}"></script> -->
@stack('scripts') <!-- Make sure this is included before the closing </body> tag -->

</body>
</html>

