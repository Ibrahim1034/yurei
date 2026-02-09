<?php

namespace App\Http\Controllers;

use App\Models\MpesaDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class AdminDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = MpesaDonation::query();
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('mpesa_receipt_number', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Date filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $donations = $query->paginate(20);
        
        // Summary statistics
        $totalDonations = MpesaDonation::completed()->sum('amount');
        $totalTransactions = MpesaDonation::count();
        $successfulTransactions = MpesaDonation::completed()->count();
        $pendingTransactions = MpesaDonation::pending()->count();

        return view('admin.donations.index', compact(
            'donations', 
            'totalDonations', 
            'totalTransactions',
            'successfulTransactions',
            'pendingTransactions'
        ));
    }

    public function financialReport(Request $request)
    {
        $query = MpesaDonation::completed();
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $donations = $query->orderBy('created_at', 'desc')->get();
        
        // Summary data
        $totalAmount = $donations->sum('amount');
        $totalCount = $donations->count();
        $averageDonation = $totalCount > 0 ? $totalAmount / $totalCount : 0;
        
        // Monthly breakdown
        $monthlyData = $donations->groupBy(function($donation) {
            return $donation->created_at->format('Y-m');
        })->map(function($monthDonations) {
            return [
                'count' => $monthDonations->count(),
                'amount' => $monthDonations->sum('amount')
            ];
        });

        return view('admin.donations.financial-report', compact(
            'donations',
            'totalAmount',
            'totalCount',
            'averageDonation',
            'monthlyData'
        ));
    }

    public function exportFinancialReport(Request $request)
    {
        $query = MpesaDonation::completed();
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $donations = $query->orderBy('created_at', 'desc')->get();
        $totalAmount = $donations->sum('amount');
        $totalCount = $donations->count();
        
        // Generate filename with date range if applicable
        $filename = 'donations-report-' . date('Y-m-d');
        if ($request->has('date_from') && $request->has('date_to')) {
            $filename = 'donations-report-' . $request->date_from . '-to-' . $request->date_to;
        }
        
        $pdf = PDF::loadView('admin.donations.pdf-report', compact(
            'donations',
            'totalAmount',
            'totalCount'
        ));
        
        return $pdf->download($filename . '.pdf');
    }

    public function show(MpesaDonation $donation)
    {
        return view('admin.donations.show', compact('donation'));
    }
}