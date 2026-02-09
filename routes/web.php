<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MpesaSTKPUSHController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\MembershipCardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\EventPaymentController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminDonationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramPaymentController;
use App\Http\Controllers\ProgramRegistrationController;
use Illuminate\Contracts\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Check if user is active
    if (auth()->check() && !auth()->user()->is_active) {
        return redirect()->route('payment.create', auth()->user())
            ->with('error', 'Please complete your payment to access the dashboard.');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    // Debug info
    \Log::info('Admin dashboard accessed by user: ' . auth()->id());
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

// Events Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('events', EventController::class);
});

// In your routes/web.php file, update the event payment routes:

// Event Registration Routes - Accessible to all (authenticated and guests)
Route::prefix('events')->group(function () {
    // Show registration form - accessible to all
    Route::get('/{event}/register', [EventRegistrationController::class, 'showRegistrationForm'])
        ->name('event.registration.form');
    
    // Process registration - accessible to all
    Route::post('/{event}/register', [EventRegistrationController::class, 'processRegistration'])
        ->name('event.registration.process');
    
    // Show registration success page
    Route::get('/registration/success/{registration}', [EventRegistrationController::class, 'showSuccess'])
        ->name('event.registration.success');
});

// In web.php - Add this route
Route::post('/events/{event}/register/retry', [EventRegistrationController::class, 'retryPayment'])
    ->name('event.registration.retry');

// Event payment routes
Route::post('/event-payment/initiate', [App\Http\Controllers\EventPaymentController::class, 'initiatePayment'])->name('event.payment.initiate');
Route::get('/event-payment/status', [App\Http\Controllers\EventPaymentController::class, 'checkPaymentStatus'])->name('event.payment.status');
Route::post('/event-payment/query', [App\Http\Controllers\EventPaymentController::class, 'stkQuery'])->name('event.payment.query');

// Event Payment Routes - Add these outside the events prefix
Route::prefix('event')->group(function () {

    Route::post('/payment/confirm', [EventPaymentController::class, 'confirmPayment'])->name('event.payment.confirm');
});

// Documents Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
});

// Add this to your existing routes, preferably in the admin section
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/membership-card', [UserController::class, 'viewMembershipCard'])->name('users.membership-card');
});

// Add these routes to your web.php file
Route::middleware(['auth'])->group(function () {
    Route::resource('leadership', LeadershipController::class);
});

Route::middleware(['auth', 'verified'])->prefix('programs')->group(function () {
    // Resource routes
    Route::get('/', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::post('/', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/{program}', [ProgramController::class, 'show'])->name('programs.show');
    Route::get('/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/{program}', [ProgramController::class, 'update'])->name('programs.update');
    Route::delete('/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');
    
    // Attendance Management Routes - EXPLICITLY DEFINED
    Route::get('/attendants/overview', [ProgramController::class, 'attendants'])->name('programs.attendants');
    Route::get('/{program}/attendants', [ProgramController::class, 'showAttendants'])->name('programs.attendants.show');
    Route::post('/{program}/attendants/mark', [ProgramController::class, 'markAttendance'])->name('programs.attendants.mark');
    Route::get('/{program}/attendants/export', [ProgramController::class, 'exportAttendants'])->name('programs.attendants.export');
});

// User-facing programs list (no admin controls) - MOVE THIS OUTSIDE
Route::get('/programs-list', [ProgramController::class, 'userList'])->name('programs.list');

// Program Registration Routes - Accessible to all
Route::prefix('programs')->group(function () {

    Route::get('/{program}/register', [ProgramRegistrationController::class, 'showRegistrationForm'])
        ->name('program.registration.form');
    
    Route::post('/{program}/register', [ProgramRegistrationController::class, 'processRegistration'])
        ->name('program.registration.process');
});

// Program payment routes
Route::post('/program-payment/initiate', [ProgramPaymentController::class, 'initiatePayment'])->name('program.payment.initiate');
Route::get('/program-payment/status', [ProgramPaymentController::class, 'checkPaymentStatus'])->name('program.payment.status');
Route::post('/program-payment/query', [ProgramPaymentController::class, 'stkQuery'])->name('program.payment.query');

// Invitations Management Routes
Route::prefix('admin/invitations')->middleware(['auth'])->group(function () {
    Route::get('/', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/send', [InvitationController::class, 'sendInvitations'])->name('invitations.send');
    Route::get('/event/{event}/registrations', [InvitationController::class, 'showEventRegistrations'])->name('invitations.event.registrations');
    Route::get('/event/{event}/export', [InvitationController::class, 'exportAttendanceReport'])->name('invitations.export');
    Route::get('/event/{event}/attendance-data', [InvitationController::class, 'getAttendanceData'])->name('invitations.attendance.data');
    Route::post('/{registration}/mark-attendance', [InvitationController::class, 'markAttendance'])->name('invitations.mark.attendance');
});

// User Invitations Route
Route::middleware(['auth', 'verified'])->get('/my-invitations', [App\Http\Controllers\UserController::class, 'myInvitations'])->name('user.invitations');

// Donation Routes
Route::prefix('donations')->group(function () {
    Route::get('/', [DonationController::class, 'showDonationForm'])->name('donations.form');
    Route::post('/initiate', [DonationController::class, 'initiateDonation'])->name('donations.initiate');
    Route::get('/status', [DonationController::class, 'checkDonationStatus'])->name('donations.status');
    Route::post('/query', [DonationController::class, 'stkQuery'])->name('donations.query');
    Route::any('/callback', [DonationController::class, 'handleCallback'])->name('donations.callback');
});

// Admin Donation Routes
Route::prefix('admin/donations')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminDonationController::class, 'index'])->name('admin.donations.index');
    Route::get('/financial-report', [AdminDonationController::class, 'financialReport'])->name('admin.donations.financial-report');
    Route::get('/export-financial-report', [AdminDonationController::class, 'exportFinancialReport'])->name('admin.donations.export-financial-report');
    Route::get('/{donation}', [AdminDonationController::class, 'show'])->name('admin.donations.show');
});

// Gallery Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('gallery', GalleryController::class);
    Route::post('/gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('gallery.toggle-status');
});

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// In web.php - Update the admin dashboard route
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->middleware(['auth'])->name('admin.dashboard');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Separate profile update routes
    Route::put('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name.update');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');
    Route::put('/profile/phone', [ProfileController::class, 'updatePhone'])->name('profile.phone.update');
    Route::put('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Membership Card Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/membership-card/create', [MembershipCardController::class, 'create'])->name('membership-card.create');
    Route::post('/membership-card/store', [MembershipCardController::class, 'store'])->name('membership-card.store');
    Route::get('/membership-card', [MembershipCardController::class, 'show'])->name('membership-card.show');
    Route::get('/membership-card/download', [MembershipCardController::class, 'downloadPdf'])->name('membership-card.download');
    Route::get('/membership-card/print', [MembershipCardController::class, 'print'])->name('membership-card.print');
    Route::get('/membership-card/export/{type}', [MembershipCardController::class, 'export'])->name('membership-card.export');
});



Route::get('/payment/{user}/create', [PaymentController::class, 'create'])->name('payment.create');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/processing', [PaymentController::class, 'processing'])->name('payment.processing');

Route::get('/v1/status', [MpesaSTKPUSHController::class, 'checkStatus'])->name('mpesa.status');

// M-Pesa STK Push routes
Route::post('/v1/mpesa/stk/push', [MpesaSTKPUSHController::class, 'STKPush'])->name('mpesa.stk.push');
Route::post('/v1/mpesa/stk/query', [MpesaSTKPUSHController::class, 'stkQuery'])->name('mpesa.stk.query');
Route::post('v1/confirm', [MpesaSTKPUSHController::class, 'STKConfirm'])->name('mpesa.confirm');

// M-Pesa webhook routes (publicly accessible) - NO MIDDLEWARE
Route::any('/webhook/mpesa/confirm', [MpesaSTKPUSHController::class, 'STKConfirm'])->name('mpesa.confirm');
Route::any('/webhook/payment/validation', [PaymentController::class, 'validateTransaction'])
    ->name('webhook.payment.validation');
Route::any('/webhook/payment/confirmation', [PaymentController::class, 'confirmTransaction'])
    ->name('webhook.payment.confirmation');



// Test route
Route::get('/webhook/test', function() {
    return response()->json([
        'status' => 'online',
        'message' => 'Webhook is accessible',
        'timestamp' => now()
    ]);
});

require __DIR__.'/auth.php';