<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/leads', [LeadController::class, 'index']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads/{lead}', [LeadController::class, 'show']);
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);
    Route::post('/leads/{lead}/contacted', [LeadController::class, 'markContacted']);
    Route::get('/dashboard', [LeadController::class, 'dashboard']);
});
