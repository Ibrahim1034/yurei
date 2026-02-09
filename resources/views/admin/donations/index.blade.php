
<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-currency-dollar me-2"></i>Donations Management
            </h2>
            <div>
                <a href="{{ route('admin.donations.financial-report') }}" class="btn btn-info me-2">
                    <i class="bi bi-graph-up me-1"></i>Financial Report
                </a>
            </div>
        </div>
        
        <div class="container">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto bg-success">
                            <i class="bi bi-currency-dollar text-white"></i>
                        </div>
                        <h5>Total Donations</h5>
                        <h3 class="text-success">KES {{ number_format($totalDonations, 2) }}</h3>
                        <p class="text-muted small">All Time</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto bg-primary">
                            <i class="bi bi-receipt text-white"></i>
                        </div>
                        <h5>Total Transactions</h5>
                        <h3 class="text-primary">{{ number_format($totalTransactions) }}</h3>
                        <p class="text-muted small">All Transactions</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto bg-info">
                            <i class="bi bi-check-circle text-white"></i>
                        </div>
                        <h5>Successful</h5>
                        <h3 class="text-info">{{ number_format($successfulTransactions) }}</h3>
                        <p class="text-muted small">Completed</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto bg-warning">
                            <i class="bi bi-clock text-white"></i>
                        </div>
                        <h5>Pending</h5>
                        <h3 class="text-warning">{{ number_format($pendingTransactions) }}</h3>
                        <p class="text-muted small">Awaiting Payment</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="dashboard-card mb-4">
                <form method="GET" action="{{ route('admin.donations.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Name, Phone, Receipt..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sort By</label>
                            <select name="sort_field" class="form-select">
                                <option value="created_at" {{ request('sort_field') == 'created_at' ? 'selected' : '' }}>Date</option>
                                <option value="amount" {{ request('sort_field') == 'amount' ? 'selected' : '' }}>Amount</option>
                                <option value="donor_name" {{ request('sort_field') == 'donor_name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Donor</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Receipt No</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donations as $donation)
                                <tr>
                                    <td>{{ $donation->id }}</td>
                                    <td>
                                        {{ $donation->donor_name ?? 'Anonymous' }}
                                    </td>
                                    <td>{{ $donation->phone_number }}</td>
                                    <td>
                                        <strong>KES {{ number_format($donation->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $donation->status == 'completed' ? 'success' : ($donation->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($donation->mpesa_receipt_number)
                                            <code>{{ $donation->mpesa_receipt_number }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $donation->created_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.donations.show', $donation) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="mt-2 text-muted">No donations found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($donations->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $donations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>