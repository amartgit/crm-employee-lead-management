@if (Auth::check())
    @if (Auth::user()->role === 'superadmin')
        <!-- Superadmin Dashboard -->
        <a href="{{ route('superadmin.index') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>

        <!-- Superadmin Register New User -->
        <a href="{{ route('auth.register') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Register New User</a>


        <!-- Superadmin My Profile -->
        <a href="{{ route('auth.profile') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>My Profile</a>


        <!-- Superadmin Manage Users -->
        <a href="{{ route('superadmin.manage_users') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Manage Users</a>


    @elseif (Auth::user()->role === 'admin')
        <!-- Admin Dashboard -->
        <a href="{{ route('admin.index') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Dashboard</a>

        <!-- Admin Register New User -->
        <a href="{{ route('auth.register') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Register New User</a>


        <!-- Admin My Profile -->
        <a href="{{ route('auth.profile') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>My Profile</a>

        <!-- Admin Manage Users -->
        <a href="{{ route('admin.manage_users') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Manage Users</a>


    @elseif (Auth::user()->role === 'user')
        <!-- User My Profile -->
        <a href="{{ route('auth.profile') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>My Profile</a>

    @else
        <!-- Welcome for Guests -->
        <a href="{{ route('welcome') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Welcome</a>

    @endif

    <!-- Logout Button -->
    <a class="nav-item nav-link">
        <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit">
            <i class="fa fa-th me-2"></i>

                <span>Logout</span>
            </button>
        </form>
    </a>

@else
    <!-- Login Link for Guests -->
    <a href="{{ route('auth.login') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Login</a>

@endif
