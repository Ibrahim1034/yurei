<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donations Financial Report - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-card {
            flex: 1;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            padding: 15px;
            margin: 0 10px;
            text-align: center;
        }
        .summary-card:first-child {
            margin-left: 0;
        }
        .summary-card:last-child {
            margin-right: 0;
        }
        .summary-card h3 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
        }
        .summary-card p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #34495e;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ecf0f1;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-row {
            background-color: #2c3e50 !important;
            color: white;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 10px;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
        .section-title {
            background-color: #ecf0f1;
            padding: 10px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            color: #2c3e50;
            border-left: 4px solid #3498db;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-completed {
            background-color: #27ae60;
            color: white;
        }
        .badge-pending {
            background-color: #f39c12;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>YUREI SYSTEM - DONATIONS FINANCIAL REPORT</h1>
        <div class="subtitle">
            Generated on: {{ date('F j, Y g:i A') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section-title">SUMMARY STATISTICS</div>
    <div class="summary-cards">
        <div class="summary-card">
            <h3>KES {{ number_format($totalAmount, 2) }}</h3>
            <p>Total Donations</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($totalCount) }}</h3>
            <p>Total Transactions</p>
        </div>
        <div class="summary-card">
            <h3>KES {{ number_format($totalCount > 0 ? $totalAmount / $totalCount : 0, 2) }}</h3>
            <p>Average Donation</p>
        </div>
    </div>

    <!-- Donation Transactions -->
    <div class="section-title">DONATION TRANSACTIONS</div>
    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Donor Name</th>
                <th>Phone Number</th>
                <th class="text-right">Amount (KES)</th>
                <th>Receipt Number</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $donation)
                <tr>
                    <td>{{ $donation->created_at->format('M j, Y g:i A') }}</td>
                    <td>{{ $donation->donor_name ?? 'Anonymous' }}</td>
                    <td>{{ $donation->phone_number }}</td>
                    <td class="text-right">{{ number_format($donation->amount, 2) }}</td>
                    <td>{{ $donation->mpesa_receipt_number ?? '-' }}</td>
                    <td>
                        @if($donation->status === 'completed')
                            <span class="badge badge-completed">Completed</span>
                        @elseif($donation->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @else
                            <span style="color: #e74c3c;">{{ ucfirst($donation->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No donation transactions found</td>
                </tr>
            @endforelse
        </tbody>
        @if($donations->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="3"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>KES {{ number_format($totalAmount, 2) }}</strong></td>
                    <td colspan="2"><strong>{{ $totalCount }} transaction(s)</strong></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Monthly Breakdown (if applicable) -->
    @php
        $monthlyData = $donations->groupBy(function($donation) {
            return $donation->created_at->format('Y-m');
        })->map(function($monthDonations) {
            return [
                'count' => $monthDonations->count(),
                'amount' => $monthDonations->sum('amount')
            ];
        });
    @endphp

    @if($monthlyData->count() > 0)
        <div class="section-title">MONTHLY BREAKDOWN</div>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-center">Number of Donations</th>
                    <th class="text-right">Total Amount (KES)</th>
                    <th class="text-right">Average Amount (KES)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $month => $data)
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-right">{{ number_format($data['amount'], 2) }}</td>
                        <td class="text-right">{{ number_format($data['amount'] / $data['count'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Generated by YUREI System | Page 1 of 1</p>
        <p>This is an automated report. For any inquiries, please contact system administration.</p>
    </div>
</body>
</html>