<?php
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MpesaSTKPUSHController;

// M-Pesa callback routes
Route::post('v1/confirm', [MpesaSTKPUSHController::class, 'STKConfirm'])->name('api.mpesa.confirm');
Route::get('v1/status', [MpesaSTKPUSHController::class, 'checkStatus'])->name('api.mpesa.status');

// Route::post('/webhook/payment', [PaymentController::class, 'webhook']);
// Route::post('/webhook/validate', [PaymentController::class, 'validateTransaction']);
// Route::post('/webhook/confirm', [PaymentController::class, 'confirmTransaction']);
