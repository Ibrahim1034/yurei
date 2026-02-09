<x-app-layout>
    <x-slot name="header">
       

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-check-circle me-2"></i>Registration Successful
            </h2>
            <a href="{{ url('/#events') }}" class="btn btn-outline-secondary">
                <i class="bi bi-calendar-event me-1"></i>View Events
            </a>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="dashboard-card text-center">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success display-1"></i>
                        </div>
                        
                        <h3 class="text-success mb-3">Registration Confirmed!</h3>
                        
                        <div class="card border-success mb-4">
                            <div class="card-body">
                                <h5 class="card-title">{{ $registration->event->title }}</h5>
                                <div class="row text-start small">
                                    <div class="col-4 text-muted">Date:</div>
                                    <div class="col-8">{{ $registration->event->event_date->format('M d, Y h:i A') }}</div>
                                    
                                    <div class="col-4 text-muted">Venue:</div>
                                    <div class="col-8">{{ $registration->event->venue }}</div>
                                    
                                    <div class="col-4 text-muted">Registration ID:</div>
                                    <div class="col-8">EVENT-{{ $registration->id }}</div>
                                    
                                    @if($registration->event->is_paid)
                                    <div class="col-4 text-muted">Amount Paid:</div>
                                    <div class="col-8">KES {{ number_format($registration->amount_paid, 2) }}</div>
                                    
                                    @if($registration->mpesa_receipt_number)
                                    <div class="col-4 text-muted">M-Pesa Receipt:</div>
                                    <div class="col-8">{{ $registration->mpesa_receipt_number }}</div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <p class="text-muted mb-4">
                            You will receive an email confirmation shortly with event details.
                        </p>

                        <div class="d-grid gap-2">
                            <a href="{{ url('/#events') }}" class="btn btn-primary">
                                <i class="bi bi-calendar-event me-1"></i>View More Events
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-speedometer2 me-1"></i>Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>