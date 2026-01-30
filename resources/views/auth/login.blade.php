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
    <title>@yield('title')</title>
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


</head>
<body>

<div class="container-fluid">
    <div class="container p-2 py-5 p-lg-5">
        <div class="center verticle_center">
            <div class="login_section border" style="width:auto;">
                <div class="logo_login">
                    <div class="center">
                        <img width="180" class="rounded" src="images/logo/logo.png" alt="#" />
                    </div>
                </div>
                <div class="login_form ">
                <p class="text-muted pb-3 text-center h2">Welcome, to Vibrant Idea!</p>
                @if(session('message'))
                        <div class="alert alert-warning">
                        {{ session('message') }}
                    </div>
                @endif
                <div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="employee_id" class="form-label h5">Employee ID : </label>
                            <input type="text" name="employee_id" id="employee_id" class="form-control" required>
                            @error('employee_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 ">
                            <label for="password" class="form-label  h5">Password :</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center py-1">
                            <button type="submit" class="btn-lg btn-success my-2">Login</button>

                            <button type="reset" class="btn-lg btn-primary my-2">clear</button>

                        </div>
                    </form>
                    </div>
                    
                </div>
                <div class="text-right p-2">
                        <a href="{{ route('password.request') }}" class="text-info text-end mx-2"> forgot Password</a>
                    </div>
            </div>
        </div>
    </div>

</div>

<!-- jQuery -->

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
<!-- chart js -->
<!-- <script src="{{ url('js/Chart.min.js') }}"></script>
        <script src="{{ url('js/Chart.bundle.min.js') }}"></script>
        <script src="{{ url('js/utils.js') }}"></script>
        <script src="{{ url('js/analyser.js') }}"></script> -->
<!-- nice scrollbar -->
<script src="{{ url('js/perfect-scrollbar.min.js') }}"></script>
<script>
    if (!localStorage.getItem('device_token')) {
        localStorage.setItem('device_token', 'dev-' + Math.random().toString(36).substring(2));
    }

    document.querySelector('form').addEventListener('submit', function () {
        let hiddenToken = document.createElement('input');
        hiddenToken.type = 'hidden';
        hiddenToken.name = 'device_token';
        hiddenToken.value = localStorage.getItem('device_token');
        this.appendChild(hiddenToken);
    });
</script>

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
