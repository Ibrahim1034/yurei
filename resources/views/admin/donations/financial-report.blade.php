
<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

        <div class="d-flex justify-content-between align-items-center my-5 px-5 my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-graph-up me-2"></i>Financial Report - Donations
            </h2>
            <div>
                <a href="{{ route('admin.donations.export-financial-report', request()->query()) }}" 
                   class="btn btn-danger me-2">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Transactions
                </a>
            </div>
        </div>
        <div class="container">
            <!-- Report Filters -->
            <div class="dashboard-card mb-4">
                <h5 class="mb-3">Report Filters</h5>
                <form method="GET" action="{{ route('admin.donations.financial-report') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">Generate Report</button>
                                <a href="{{ route('admin.donations.financial-report') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="dashboard-card text-center">
                        <h3 class="text-success">KES {{ number_format($totalAmount, 2) }}</h3>
                        <p class="text-muted">Total Donations</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card text-center">
                        <h3 class="text-primary">{{ number_format($totalCount) }}</h3>
                        <p class="text-muted">Total Transactions</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card text-center">
                        <h3 class="text-info">KES {{ number_format($averageDonation, 2) }}</h3>
                        <p class="text-muted">Average Donation</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Breakdown -->
            @if($monthlyData->count() > 0)
            <div class="dashboard-card mb-4">
                <h5 class="mb-3">Monthly Breakdown</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Number of Donations</th>
                                <th>Total Amount</th>
                                <th>Average Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyData as $month => $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                    <td>{{ $data['count'] }}</td>
                                    <td>KES {{ number_format($data['amount'], 2) }}</td>
                                    <td>KES {{ number_format($data['amount'] / $data['count'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Detailed Transactions -->
            <div class="dashboard-card">
                <h5 class="mb-3">Donation Transactions</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Donor</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Receipt No</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donations as $donation)
                                <tr>
                                    <td>{{ $donation->created_at->format('M j, Y g:i A') }}</td>
                                    <td>{{ $donation->donor_name ?? 'Anonymous' }}</td>
                                    <td>{{ $donation->phone_number }}</td>
                                    <td><strong>KES {{ number_format($donation->amount, 2) }}</strong></td>
                                    <td>
                                        @if($donation->mpesa_receipt_number)
                                            <code>{{ $donation->mpesa_receipt_number }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="mt-2 text-muted">No donations found for the selected period</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>