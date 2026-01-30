@if (!Auth::check())
<script>
    window.location.href = "{{ url('/login') }}";
</script>
<?php exit; ?>
@endif

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
    <!--<link rel="stylesheet" href="{{ url('css/color.css') }}" />-->
    <!-- select bootstrap -->
    <link rel="stylesheet" href="{{ url('css/bootstrap-select.css') }}" />
    <!-- scrollbar css -->
    <link rel="stylesheet" href="{{ url('css/perfect-scrollbar.css') }}" />
    <!-- custom css -->
    <link rel="stylesheet" href="{{ url('css/custom.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- [if lt IE 9]> -->
    <!-- <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> -->
    <!-- <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script> -->
    <!-- <![endif] -->
    <style>
    /* Blinking effect for "online" status */
    .blinking-online {
        color: #28a745; /* Green color */
        font-weight: bold;
        animation: blink-animation 1s steps(5, start) infinite;
    }

    /* Keyframes for the blinking effect */
    @keyframes blink-animation {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>

    <!-- Styles / Scripts -->
    @vite(['resources/js/app.js', 'resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



</head>

<body class="dashboard dashboard_1">
    <div class="full_container">
        <div class="inner_container">
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar_blog_1 relative">
                    <div class="sidebar-header">
                        <div class="logo_section">
                            <a href=""><img class="logo_icon img-responsive"
                                    src="{{ url('images/logo/logo_icon.png') }}" alt="#" /></a>
                        </div>
                    </div>
                    <div class="sidebar_user_info sticky-top">
                        <div class="icon_setting"></div>
                        <div class="user_profle_side">
                            <div class="user_info">
                                @auth
                                <h6><span class="text-warning">{{ Auth::user()->employee_id }} </span>  </h6>

                                <p><span class="online_animation"></span> Online</p>
                                @else
                                <h6 class="mb-0">Guest</h6>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar_blog_2">

                    <h4>General</h4>
                    <ul class="list-unstyled components">
                        @if (Auth::check())
                        <li>
                            <a href="{{ route('profile.show') }}"><i class="fa fa-user"></i> My Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('attendance.dashboard') }}"><i class="fa fa-user"></i> My Attendance</a>
                        </li>
                        @if (Auth::user()->role === 'SuperAdmin')
                        <li>
                            <a href="{{ route('superadmin.index') }}">
                                <i class="fas fa-columns"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <!--<li>-->
                        <!--    <a href="{{ route('admin.ips.index') }}">-->
                        <!--        <i class="fas fa-list"></i>-->
                        <!--        <span>Page Access IP</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                        <li>
                            <a href="#maleads" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                                    class="fas fa-list"></i></i> <span>Leads</span></a>
                            <ul class="collapse list-unstyled" id="maleads">

                                <li>
                                    <a href="{{ route('admin.leads.index') }}"><i class="fas fa-list"></i></i> View</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.leads.import') }}"><i class="fa fa-download"></i>
                                        Import</a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="#memployee" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                                    class="fa fa-users "></i> <span>Employee</span></a>
                            <ul class="collapse list-unstyled" id="memployee">

                                <li>
                                    <a href="{{ route('superadmin.employees.index') }}"><i class="fa fa-users"></i>
                                        Manage Employees</a>
                                </li>
                                
                                 <li>
                                    <a href="{{ route('admin.attendance.index') }}"><i class="fa fa-user"></i> Manage
                                        Attendance</a>
                                </li>
                                <li>
                                    <a href="{{ route('register.show') }}"><i class="fa fa-user"></i> Register
                                        Employee</a>
                                </li>

                              
                            </ul>
                        </li>
                        
                         <li>
                            <a href="{{ route('admin.logins') }}"><i class="fas fa-users "></i><span>
                                    Daily Logins</span></a>
                        </li>

                        @elseif (Auth::user()->role === 'Admin')
                        <li>
                            <a href="{{ route('admin.index') }}"><i class="fas fa-columns "></i><span>
                                    Dashboard</span></a>
                        </li>
                        
                        <li>
                            <a href="{{ route('admin.logins') }}"><i class="fas fa-users "></i><span>
                                    Daily Logins</span></a>
                        </li>
                       
                        
                        <!-- <li>-->
                        <!--    <a href="{{ route('admin.ips.index') }}">-->
                        <!--        <i class="fas fa-list"></i>-->
                        <!--        <span>Page Access IP</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                        <li>
                            <a href="#maleads" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                                    class="fas fa-list"></i></i> <span>Leads</span></a>
                            <ul class="collapse list-unstyled" id="maleads">

                                <li>
                                    <a href="{{ route('admin.leads.index') }}"><i class="fas fa-list"></i></i> View</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.leads.import') }}"><i class="fa fa-download"></i>
                                        Import</a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a href="#memployee" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i
                                    class="fa fa-users "></i> <span>Employee</span></a>
                            <ul class="collapse list-unstyled" id="memployee">

                                <li>
                                    <a href="{{ route('admin.attendance.index') }}"><i class="fa fa-user"></i> Manage
                                        Attendance</a>
                                </li>
                                <li>
                                    <a href="{{ route('register.show') }}"><i class="fa fa-user"></i> Register
                                        Employee</a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.employees.index') }}"><i class="fa fa-users"></i> Manage
                                        Employees</a>
                                </li>
                                
                                 <li>
                            <a href="{{ route('admin.device.tokens') }}">
                                <i class="fas fa-list"></i>
                                <span>Sales Device Access</span>
                            </a>
                        </li>
                            </ul>
                        </li>

                        @elseif (Auth::user()->role === 'Employee')
                        <li>
                            <a href="{{ route('employee.index') }}"><i class="fas fa-columns"></i><span>
                                    Dashboard</span></a>
                        </li>

                        @if (Auth::user()->employee->department === 'Sales')

                        <li>
                            <a href="{{ route('employee.leads.index') }}"><i class="fa fa-list"></i><span>
                                    Leads</span></a>
    
                        <li>
                            <a href="{{ route('employee.leads.myleads') }}"><i class="fa fa-list "></i><span>
                                    myleads</span></a>
                        </li>
                        @endif
                        @else

                        <li><a href=""><i class="fa fa-table purple_color2"></i> <span>Tables</span></a></li>

                        @endif
                        <li>
                            <a class="bg-dark">
                                <span>
                                    <!-- Logout Button -->
                                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button id="logoutButton" type="submit" class="btn btn-primary d-flex flex-wrap">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span class="">Logout</span>
                                        </button>
                                    </form>
                                </span>
                            </a>
                        </li>
                        @else
                        <!-- Login Link for Guests -->
                        <li>
                            <a href="{{ route('login') }}"><i class="fa fa-th me-2"></i><span>Login</span></a>
                        </li>
                        @endif


                    </ul>
                </div>
            </nav>
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
                <!-- topbar -->
                <div class="topbar">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="full">
                            <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i
                                    class="fa fa-bars"></i></button>
                            <div class="logo_section">
                                <a href=""><img class="img-responsive bg-light" src="{{ url('images/logo/logo.png') }}"
                                        alt="#" /></a>
                            </div>
                            <div style="float: right;" class="p-2 d-flex align-items-center" style="padding-right: 10px">
                                <span class="h6 text-center text-light py-2"> @auth
                                    <a href="{{ route('profile.show') }}" class="text-light"><i class="fa fa-user"></i>  {{ Auth::user()->name }} </a>
                                    @else
                                    Guest
                                    @endauth</span>
                            </div>
                            <div class="right_topbar">

                                <div class="icon_info">
                                    @auth
                                    <ul class="user_profile_dd px-2">
                                        <li>
                                            <a class="dropdown-toggle" data-toggle="dropdown"><span
                                                    class="name_user"> {{ Auth::user()->employee_id }}</span></a>
                                            <div class="dropdown-menu border">
                                                <a class="dropdown-item" href="{{ route('profile.show') }}"> My Profile </a>
                                                <a class="dropdown-item text-center"><span>
                                                        <form action="{{ route('logout') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" title="logout" class="btn btn-primary w-100">
                                                                <i class="fas fa-sign-out-alt"></i></i> logout
                                                            </button>
                        

                                                        </form>
                                                    </span>
                                                </a>
                                            </div>
                                        </li>

                                    </ul>

                                    <!-- <ul>
                                                            <li><a href="#"><i class="fa fa-bell-o"></i><span class="badge">2</span></a></li>
                                                            <li><a href="#"><i class="fa fa-question-circle"></i></a></li>
                                                            <li><a href="#"><i class="fa fa-envelope-o"></i><span class="badge">3</span></a></li>
                                                        </ul> -->

                                    @else
                                    <ul>
                                        <li><a href="#"><i class="fa fa-question-circle"></i></a></li>
                                    </ul>
                                    @endauth
                                </div>

                            </div>
                        </div>
                    </nav>
                </div>
                <!-- end topbar -->

                <!-- dashboard inner -->
                <div class="midde_cont">
                    <!-- <p class="text-end"><span id="date-time-display"></span></p> -->
                    <div class="overflow-y-auto overflow-x-auto max-h-screen custom-scrollbar"
                        style="max-height: 80vh;">
                        <div style="min-height:90vh">

                            @yield('content')
                        </div>

                        <div class="footer border-top">
                            <p>Developed by <a target="_blank" href="https://www.kumarinfotech.net/"
                                    rel="noopener noreferrer" aria-label="Visit Kumarinfotech website">Kumarinfotech</a>
                                &copy;
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                            </p>
                        </div>
                    </div>


                    <!-- footer -->
                    <!-- <div class="fixed-bottom">
                        <div class="footer">
                            <p>Developed by <a target="_blank" href="https://www.kumarinfotech.net/"
                                    rel="noopener noreferrer" aria-label="Visit Kumarinfotech website">Kumarinfotech</a>
                                &copy;
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                            </p>
                        </div>
                    </div> -->
                </div>
                <!-- end dashboard inner -->

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
// Set the inactivity time thresholds (in milliseconds)
const inactivityThreshold = 120 * 60 * 1000; // 15 minutes (in ms)
const logoutThreshold = 121 * 60 * 1000; // 16 minutes (in ms)
const inactivityWarningTimeout = 20 * 1000; // Timeout for inactivity warning (20 seconds)

let inactivityTimer;
let logoutTimer;
let inactivityWarningTimer;

// Function to reset inactivity timer
function resetTimers() {

    // Clear existing timers
    clearTimeout(inactivityTimer);
    clearTimeout(logoutTimer);
    clearTimeout(inactivityWarningTimer);

    // Set new inactivity timer
    inactivityTimer = setTimeout(showInactivityWarning, inactivityThreshold);
    //console.log("Inactivity timer set for " + inactivityThreshold / 1000 + " seconds.");
}

// Function to show warning after 9 minutes of inactivity
function showInactivityWarning() {
    // console.log("Inactivity threshold reached, showing warning.");

    // Set a timeout to log out the user if they don't respond to SweetAlert
    inactivityWarningTimer = setTimeout(() => {
        // console.log("No response within warning period. Logging out user.");
        logoutUser();
    }, inactivityWarningTimeout);

    // Show SweetAlert2 confirmation dialog
    Swal.fire({
        title: 'You have been inactive for a long period.',
        text: 'Do you want to continue? You will be logged out if no response.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Log Out',
        allowOutsideClick: false, // Prevent closing the modal by clicking outside
        allowEscapeKey: false,  // Prevent closing by pressing ESC
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        clearTimeout(inactivityWarningTimer); // Clear the timeout if the user responds

        if (result.isConfirmed) {
            // console.log("User confirmed to continue.");
            resetTimers(); // Reset the inactivity timer
        } else {
            // console.log("User chose to log out.");
            logoutUser(); // Log out immediately
        }
    });
}

// Function to log the user out
function logoutUser() {
    // console.log("Logging out user...");

    // Trigger the form submission to logout the user
    document.getElementById('logoutForm').submit();
}

// Listen for user activity (mouse move, key press, etc.)
window.onload = function() {
    // console.log("Page loaded. Initializing inactivity timer.");
    resetTimers();  // Initialize inactivity timer on page load
};
document.onmousemove = function() {
    // console.log("Mouse move detected. Resetting inactivity timer.");
    resetTimers();
};
document.onkeydown = function() {
    // console.log("Key press detected. Resetting inactivity timer.");
    resetTimers();
};
document.onclick = function() {
    // console.log("Click detected. Resetting inactivity timer.");
    resetTimers();
};
document.onscroll = function() {
    // console.log("Scroll detected. Resetting inactivity timer.");
    resetTimers();
};



// Add a click event listener to the logout button
document.getElementById('logoutButton').addEventListener('click', function() {
    // console.log("Logout button clicked.");
    // Call the logout function directly when the user clicks the button
    logoutUser();
});

// Handle the tab or window close event to ensure the backend is notified
window.onbeforeunload = function() {
    // Use a beacon or send an asynchronous request to log out the user
    navigator.sendBeacon('/logout', {
        method: 'POST',
        body: JSON.stringify({ logout: true })
    });
    // You can also consider logging the user out on the server-side
};

</script>

        <script>
            var ps = new PerfectScrollbar('#sidebar');

            $('.alpha-only').bind('keyup blur', function() {
                $(this).val($(this).val().replace(/[^A-Za-z_\s]/, ''));
            });
            $('.number-only').bind('keyup blur', function() {
                $(this).val($(this).val().replace(/[^0-9\+\s\-\()]/, ''));
            });
            
    document.addEventListener('contextmenu', function(event) {
      event.preventDefault(); // Prevent the default context menu
    });
    
    // Prevent text selection
document.addEventListener('selectstart', function(event) {
    event.preventDefault(); // Prevent text selection
});

// Prevent copying (Ctrl+C and other copy actions)
document.addEventListener('copy', function(event) {
    event.preventDefault(); // Prevent the copying of selected text
});
        </script>
        
        <!-- custom js -->
        <script src="{{ url('js/custom.js') }}"></script>
        
        <!-- <script src="{{ url('js/chart_custom_style1.js') }}"></script> -->
        @stack('scripts') <!-- Make sure this is included before the closing </body> tag -->


</body>

</html>
