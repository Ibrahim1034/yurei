<x-guest-layout>
    <form method="POST" action="{{ route('register.complete') }}">
        @csrf
        
        <!-- Hidden fields to preserve data -->
        <input type="hidden" name="confirmed" value="1">

        <div class="text-center mb-4">
            <h4>Review Your Profile Photo</h4>
            <p class="text-muted">You can continue with registration or go back to change details</p>
        </div>

        <!-- Profile Photo Preview -->
        <div class="text-center mb-4">
            <div class="mb-3">
                @if(!empty($userData['profile_picture_data']))
                    <img id="profile-preview" src="{{ $userData['profile_picture_data'] }}" 
                         class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" 
                         alt="Profile preview">
                @else
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 150px; height: 150px;">
                        <span class="text-white">No image</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Info Summary -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title">Registration Details</h6>
                <div class="row small">
                    <div class="col-4 text-muted">Name:</div>
                    <div class="col-8">{{ $userData['name'] }}</div>
                    
                    <div class="col-4 text-muted">Email:</div>
                    <div class="col-8">{{ $userData['email'] }}</div>
                    
                    <div class="col-4 text-muted">Phone:</div>
                    <div class="col-8">{{ $userData['phone_number'] }}</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                ← Back to Edit
            </a>
            <button type="submit" class="btn btn-primary">
                Complete Registration →
            </button>
        </div>
    </form>
</x-guest-layout>