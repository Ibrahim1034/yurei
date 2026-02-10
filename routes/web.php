<?php

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
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root Route
Route::get('/', function () {
    return view('welcome');
});

// ============== AUTH ROUTES ==============
// GET routes for viewing forms (Guests only)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');
});

// POST routes for processing forms (Moved outside 'guest' middleware to fix registration loop)
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('register', [RegisteredUserController::class, 'store']); // <--- CRITICAL FIX

// Add this with your other routes
Route::post('/check-phone-availability', [RegisteredUserController::class, 'checkPhoneAvailability'])
    ->name('check.phone.availability');

Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->name('password.email');
Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    ->name('password.store');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ============== PAYMENT ROUTES ==============
Route::get('/payment/{user}/create', [PaymentController::class, 'create'])->name('payment.create');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/processing', [PaymentController::class, 'processing'])->name('payment.processing');
Route::get('/payment/{user}/retry', [PaymentController::class, 'retryPayment'])->name('payment.retry');

// ============== M-PESA ROUTES ==============
Route::get('/v1/status', [MpesaSTKPUSHController::class, 'checkStatus'])->name('mpesa.status');
Route::post('/v1/mpesa/stk/push', [MpesaSTKPUSHController::class, 'STKPush'])->name('mpesa.stk.push');
Route::post('/v1/mpesa/stk/query', [MpesaSTKPUSHController::class, 'stkQuery'])->name('mpesa.stk.query');

// M-Pesa webhook routes (publicly accessible, CSRF exempt)
Route::post('/v1/mpesa/confirm', [MpesaSTKPUSHController::class, 'STKConfirm'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('mpesa.confirm');

Route::any('/webhook/payment/validation', [PaymentController::class, 'validateTransaction'])
    ->name('webhook.payment.validation');
Route::any('/webhook/payment/confirmation', [PaymentController::class, 'confirmTransaction'])
    ->name('webhook.payment.confirmation');

// ============== DASHBOARD ROUTES ==============
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->middleware(['auth'])->name('admin.dashboard');

// ============== PROFILE ROUTES ==============
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Separate profile update routes
    Route::put('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name.update');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');
    Route::put('/profile/phone', [ProfileController::class, 'updatePhone'])->name('profile.phone.update');
    Route::put('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// ============== PROTECTED RESOURCE ROUTES ==============
Route::middleware(['auth', 'verified'])->group(function () {
    // Events
    Route::resource('events', EventController::class);

    // Documents
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Users
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/membership-card', [UserController::class, 'viewMembershipCard'])->name('users.membership-card');

    // Leadership
    Route::resource('leadership', LeadershipController::class);

    // Membership Card
    Route::get('/membership-card/create', [MembershipCardController::class, 'create'])->name('membership-card.create');
    Route::post('/membership-card/store', [MembershipCardController::class, 'store'])->name('membership-card.store');
    Route::get('/membership-card', [MembershipCardController::class, 'show'])->name('membership-card.show');
    Route::get('/membership-card/download', [MembershipCardController::class, 'downloadPdf'])->name('membership-card.download');
    Route::get('/membership-card/print', [MembershipCardController::class, 'print'])->name('membership-card.print');
    Route::get('/membership-card/export/{type}', [MembershipCardController::class, 'export'])->name('membership-card.export');

    // Gallery
    Route::resource('gallery', GalleryController::class);

    // Programs (Admin)
    Route::prefix('programs')->group(function () {
        Route::get('/', [ProgramController::class, 'index'])->name('programs.index');
        Route::get('/create', [ProgramController::class, 'create'])->name('programs.create');
        Route::post('/', [ProgramController::class, 'store'])->name('programs.store');
        Route::get('/{program}', [ProgramController::class, 'show'])->name('programs.show');
        Route::get('/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
        Route::put('/{program}', [ProgramController::class, 'update'])->name('programs.update');
        Route::delete('/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');

        // Attendance
        Route::get('/attendants/overview', [ProgramController::class, 'attendants'])->name('programs.attendants');
        Route::get('/{program}/attendants', [ProgramController::class, 'showAttendants'])->name('programs.attendants.show');
        Route::post('/{program}/attendants/mark', [ProgramController::class, 'markAttendance'])->name('programs.attendants.mark');
        Route::get('/{program}/attendants/export', [ProgramController::class, 'exportAttendants'])->name('programs.attendants.export');
    });

    // My Invitations
    Route::get('/my-invitations', [UserController::class, 'myInvitations'])->name('user.invitations');
});

// ============== PUBLIC/REGISTRATION ROUTES ==============

// Event Registration (Accessible to Authenticated Users)
Route::prefix('events')->group(function () {
    Route::get('/{event}/register', [EventRegistrationController::class, 'showRegistrationForm'])
        ->name('event.registration.form');
    Route::post('/{event}/register', [EventRegistrationController::class, 'processRegistration'])
        ->name('event.registration.process');
    Route::get('/registration/success/{registration}', [EventRegistrationController::class, 'showSuccess'])
        ->name('event.registration.success');
    Route::post('/{event}/register/retry', [EventRegistrationController::class, 'retryPayment'])
        ->name('event.registration.retry');
});

// Event Payment Endpoints
Route::post('/event-payment/initiate', [EventPaymentController::class, 'initiatePayment'])->name('event.payment.initiate');
Route::get('/event-payment/status', [EventPaymentController::class, 'checkPaymentStatus'])->name('event.payment.status');
Route::post('/event-payment/query', [EventPaymentController::class, 'stkQuery'])->name('event.payment.query');
Route::post('/event/payment/confirm', [EventPaymentController::class, 'confirmPayment'])->name('event.payment.confirm');

// Program List (Public)
Route::get('/programs-list', [ProgramController::class, 'userList'])->name('programs.list');

// Program Registration (Accessible to Authenticated Users)
Route::prefix('programs')->group(function () {
    Route::get('/{program}/register', [ProgramRegistrationController::class, 'showRegistrationForm'])
        ->name('program.registration.form');
    Route::post('/{program}/register', [ProgramRegistrationController::class, 'processRegistration'])
        ->name('program.registration.process');
});

// Program Payment Endpoints
Route::post('/program-payment/initiate', [ProgramPaymentController::class, 'initiatePayment'])->name('program.payment.initiate');
Route::get('/program-payment/status', [ProgramPaymentController::class, 'checkPaymentStatus'])->name('program.payment.status');
Route::post('/program-payment/query', [ProgramPaymentController::class, 'stkQuery'])->name('program.payment.query');

// Donations (Public Form)
Route::prefix('donations')->group(function () {
    Route::get('/', [DonationController::class, 'showDonationForm'])->name('donations.form');
    Route::post('/initiate', [DonationController::class, 'initiateDonation'])->name('donations.initiate');
    Route::get('/status', [DonationController::class, 'checkDonationStatus'])->name('donations.status');
    Route::post('/query', [DonationController::class, 'stkQuery'])->name('donations.query');
    Route::any('/callback', [DonationController::class, 'handleCallback'])->name('donations.callback');
});

// ============== ADMIN ROUTES ==============
Route::prefix('admin/donations')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminDonationController::class, 'index'])->name('admin.donations.index');
    Route::get('/financial-report', [AdminDonationController::class, 'financialReport'])->name('admin.donations.financial-report');
    Route::get('/export-financial-report', [AdminDonationController::class, 'exportFinancialReport'])->name('admin.donations.export-financial-report');
    Route::get('/{donation}', [AdminDonationController::class, 'show'])->name('admin.donations.show');
});

Route::prefix('admin/invitations')->middleware(['auth'])->group(function () {
    Route::get('/', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/send', [InvitationController::class, 'sendInvitations'])->name('invitations.send');
    Route::get('/event/{event}/registrations', [InvitationController::class, 'showEventRegistrations'])->name('invitations.event.registrations');
    Route::get('/event/{event}/export', [InvitationController::class, 'exportAttendanceReport'])->name('invitations.export');
    Route::get('/event/{event}/attendance-data', [InvitationController::class, 'getAttendanceData'])->name('invitations.attendance.data');
    Route::post('/{registration}/mark-attendance', [InvitationController::class, 'markAttendance'])->name('invitations.mark.attendance');
});

// ============== MISC ROUTES ==============
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// ============== DEBUG ROUTES ==============
Route::get('/debug/auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'user' => Auth::user() ? [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'is_active' => Auth::user()->is_active
        ] : null,
        'session_id' => session()->getId()
    ]);
});

Route::get('/debug/test-redirect/{id}', function ($id) {
    return redirect()->route('payment.create', ['user' => $id]);
});

Route::get('/test-payment/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    if (!$user) {
        return 'User not found';
    }
    return redirect()->route('payment.create', ['user' => $user->id]);
});

require __DIR__ . '/auth.php';
