<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminLeadController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ApprovedIpController;
use App\Http\Controllers\LeaveRequestController;

use Illuminate\Support\Facades\Mail;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Artisan;


// Default route if no specific role matches
Route::get('/', function () {
    if (Auth::check()) {
        // Redirect to the appropriate role's home page
        $role = Auth::user()->role;
        switch ($role) {
            case 'SuperAdmin':
                return redirect('/superadmin');
            case 'Admin':
                return redirect('/admin');
            case 'Employee':
                return redirect('/employee');
            default:
                return redirect('/');
        }
    }
    return view('index'); // Default view for unauthenticated users
});

Route::middleware([ ])->group(function () {
    Route::get('/test-ip', [LoginController::class, 'dumm'])->name('dummy.page');
    
    // Route to display login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route for login processing
Route::post('/login', [LoginController::class, 'login']);

});

Route::view('/unauthorized', 'unauthorized')->name('unauthorized');


// SuperAdmin Routes
Route::middleware(['auth', 'role:SuperAdmin','auto.logout'])->prefix('superadmin')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.index'); // Dashboard route for SuperAdmin

    Route::get('employees', [SuperAdminController::class, 'showEmployees'])->name('superadmin.employees.index');
    Route::get('employees/{employee}/edit', [SuperAdminController::class, 'editEmployee'])->name('superadmin.employees.edit');
    Route::put('employees/{employee}', [SuperAdminController::class, 'updateEmployee'])->name('superadmin.employees.update');
    Route::delete('employees/{employee}', [SuperAdminController::class, 'deleteEmployee'])->name('superadmin.employees.destroy');
    Route::get('employees/{employee}', [SuperAdminController::class, 'getEmployeeDetails'])->name('superadmin.employees.show');
    
 // Role & Department Management Routes
   // Show the form to edit role and department
Route::get('employees/{employee_id}/edit-role-dep', [SuperAdminController::class, 'editRoleDep'])->name('superadmin.employees.editRoleDep');

// Update the role and department
Route::put('employees/{employee_id}/update-role-dep', [SuperAdminController::class, 'updateRoleDep'])->name('superadmin.employees.updateRoleDep');
});


// Admin Routes
Route::middleware(['auth', 'role:Admin,SuperAdmin,','auto.logout'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index'); // Dashboard route for Admin

    Route::get('employees', [AdminController::class, 'showEmployees'])->name('admin.employees.index');
    Route::get('employees/{employee}/edit', [AdminController::class, 'editEmployee'])->name('admin.employees.edit');
    Route::put('employees/{employee}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
    Route::delete('employees/{employee}', [AdminController::class, 'deleteEmployee'])->name('admin.employees.destroy');
    Route::get('employees/{employee}', [AdminController::class, 'getEmployeeDetails'])->name('admin.employees.show');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
    Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/attendance/{id}/toggle-verification', [AdminAttendanceController::class, 'toggleVerification']) ->name('admin.attendance.toggleVerification');
    // Route::get('attendance/verify/{id}', [AdminAttendanceController::class, 'toggleVerification'])->name('admin.attendance.verify');
    // Route::put('attendance/edit/{id}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');
    Route::put('/admin/attendance/update/{id}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');
Route::post('/attendance/{id}/update-status', [AdminAttendanceController::class, 'updateAttendanceStatus'])->name('admin.attendance-d.update-status');
    

    // Monthly Attendance Management
    Route::get('/attendance/monthly', [AdminAttendanceController::class, 'monthly'])->name('admin.attendance.monthly');
    Route::get('/attendance/monthSummary', [AdminAttendanceController::class, 'monthlyAttendancereport'])->name('admin.attendance.monthSummary');
    
    // Update Attendance Status for a day
    Route::put('/attendance/update-status/{id}', [AdminAttendanceController::class, 'updateStatus'])->name('admin.attendance.update-status');
    // Store Attendance for a missing day
    Route::post('/attendance/store/{employee_id}/{date}', [AdminAttendanceController::class, 'storeForDay'])->name('admin.attendance.store');

Route::get('attendance/export-monthly', [AdminAttendanceController::class, 'exportMonthlyAttendance'])->name('admin.attendance.exportMonthly');

    Route::get('/leads', [AdminLeadController::class, 'index'])->name('admin.leads.index'); // List all leads
    Route::post('/leads', [AdminLeadController::class, 'store'])->name('admin.leads.store'); // Add new lead
    Route::match(['get', 'post'], '/leads/import', [AdminLeadController::class, 'import'])->name('admin.leads.import');
    
    Route::get('/leads/justdialleads', [AdminLeadController::class, 'showJustdialLeads'])->name('admin.leads.justdialleads');


    // Route::get('/leads/import', [AdminLeadController::class, 'showImportForm'])->name('admin.leads.import.form');
    // Route::post('/leads/import', [AdminLeadController::class, 'processImport'])->name('admin.leads.import');


    Route::get('/leads/{lead}/edit', [AdminLeadController::class, 'edit'])->name('admin.leads.edit'); // Edit lead form
    Route::put('/leads/{lead}', [AdminLeadController::class, 'update'])->name('admin.leads.update'); // Update lead
    Route::delete('/leads/{lead}', [AdminLeadController::class, 'destroy'])->name('admin.leads.destroy'); // Delete lead
    Route::post('/leads/bulk-upload', [AdminLeadController::class, 'bulkUpload'])->name('admin.leads.bulkUpload'); // Bulk upload leads
    Route::delete('/leads/bulkDestroy', [AdminLeadController::class, 'bulkDestroy'])->name('admin.leads.bulkDestroy');
    Route::get('/leads/track-activities', [AdminLeadController::class, 'trackLeadActivities'])->name('admin.leads.track-activities');
    
    Route::get('/sales-reports', [SalesReportController::class, 'index'])->name('admin.salesreports.index');

Route::get('/salesreports/export', [SalesReportController::class, 'exportExcel'])->name('admin.salesreports.export');



        Route::get('approved-ips', [ApprovedIpController::class, 'index'])->name('admin.ips.index');
    Route::post('approved-ips/store', [ApprovedIpController::class, 'store'])->name('admin.ips.store');
Route::post('/ips/bulk-update', [ApprovedIpController::class, 'bulkUpdate'])->name('admin.ips.bulk-update');

    Route::get('/logins', [LoginController::class, 'showLogins'])->name('admin.logins');


Route::get('employees/{employee_id}/edit-role-dep', [AdminController::class, 'editRoleDep'])->name('admin.employees.editRoleDep');

// Update the role and department
Route::put('employees/{employee_id}/update-role-dep', [AdminController::class, 'updateRoleDep'])->name('admin.employees.updateRoleDep');


  Route::get('/employees/{employee_id}/edit-permissions', [AdminController::class, 'editPermissions'])->name('admin.employees.editPermissions');
    Route::put('/employees/{employee_id}/update-permissions', [AdminController::class, 'updatePermissions'])->name('admin.employees.updatePermissions');
    
     Route::get('/device-tokens', [AdminController::class, 'deviceTokens'])->name('admin.device.tokens');
    Route::post('/device-tokens/update', [AdminController::class, 'updateDeviceTokens'])->name('admin.device.tokens.update');
    
    
     Route::get('/leave-requests', [LeaveRequestController::class, 'adminIndex'])->name('admin.leave.index');
    Route::post('/leave-approve/{id}', [LeaveRequestController::class, 'approve'])->name('admin.leave.approve');
    Route::post('/leave-reject/{id}', [LeaveRequestController::class, 'reject'])->name('admin.leave.reject');
    
});

// feature_access:crm
Route::middleware(['auth', 'role:Employee', 'department:Sales', 'auto.logout'])->prefix('employee')->group(function () {

         Route::middleware([])->group(function () {
                 Route::get('leads', [LeadController::class, 'index'])->name('employee.leads.index');
    Route::get('myleads', [LeadController::class, 'myleads'])->name('employee.leads.myleads');
    
    Route::get('/my-leads-activity', [LeadController::class, 'myLeadsActivity'])->name('employee.leads.my_activity');

    Route::post('leads/{id}/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
    Route::post('leads/{id}/toggle-on-call', [LeadController::class, 'toggleOnCall'])->name('leads.toggleOnCall');
    Route::post('leads/{id}/edit-actions', [LeadController::class, 'editActions'])->name('leads.editActions');
    Route::get('/leads/fetch', [LeadController::class, 'fetch'])->name('leads.fetch');
    });

});


Route::middleware(['auth', 'auto.logout', 'role:Employee,Admin,SuperAdmin' ,  'auto.logout'])->group(function () {
    Route::get('employee/', [EmployeeController::class, 'index'])->name('employee.index');

    Route::get('profile', [ProfileController::class, 'showProfile'])->name('profile.show');

    Route::get('profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');

    Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Route for showing the password update form
    Route::get('/password/change', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');

    // Route for updating the password
    Route::post('/password/change', [ProfileController::class, 'updatePasswordemp'])->name('password.updateemp');


    // Attendance Dashboard
    Route::get('attendance/my', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');

    // Check-In/Check-Out
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::post('/attendance/{id}/addnote', [AttendanceController::class, 'addNote'])->name('attendance.addnote');;
    // Log Break
    Route::post('/attendance/{attendanceId}/log-break', [AttendanceController::class, 'logBreak'])->name('attendance.logBreak');
    
    Route::get('/leave-request', [LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('/leave-request', [LeaveRequestController::class, 'store'])->name('leave.store');
    Route::get('/my-leaves', [LeaveRequestController::class, 'index'])->name('leave.index');
});


Route::middleware([])->group(function () {
    
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'verifyUser'])->name('password.verify');
Route::post('reset-password', [ForgotPasswordController::class, 'updatePassworduser'])->name('password.updateuser');

Route::get('/verify-otp', [LoginController::class, 'showOtpForm'])->name('verify.otp.form');
Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');


Route::get('/test-email', function () {
    Mail::raw('This is a test email to check if mail is working.', function ($message) {
        $message->to('amar01kit@gmail.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

