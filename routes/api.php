<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// // Example API route
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Add your Justdial leads API route
use App\Http\Controllers\Admin\AdminLeadController;
// Route::post('/receive-justdialleads', [AdminLeadController::class, 'receivejustdialleads']);

Route::post('/receive-justdialleads', [AdminLeadController::class, 'receivejustdialleads']);
Route::get('/receive-justdialleads', [AdminLeadController::class, 'receivejustdialleads']);