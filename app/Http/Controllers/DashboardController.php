<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\MembershipCard;
use App\Models\Payment;
use App\Models\MpesaDonation;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Calculate total revenue from payments and donations
        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        $totalDonations = MpesaDonation::where('status', 'completed')->sum('amount');
        $totalRevenue = $totalPayments + $totalDonations;

        // You can add more data fetching here as needed
        $stats = [
            'total_members' => 1250,
            'upcoming_events' => 24,
            'available_documents' => 15,
            'program_success_rate' => 98,
        ];

        $recentActivities = [
            [
                'type' => 'profile_update',
                'message' => 'Profile Updated',
                'time' => '2 hours ago',
                'icon' => 'check-lg',
                'color' => 'success'
            ],
            [
                'type' => 'event_registration',
                'message' => 'Registered for Event',
                'time' => '1 day ago',
                'icon' => 'calendar-check',
                'color' => 'primary'
            ],
            [
                'type' => 'document_download',
                'message' => 'Document Downloaded',
                'time' => '3 days ago',
                'icon' => 'file-text',
                'color' => 'info'
            ]
        ];

        $upcomingEvents = [
            [
                'title' => 'YUREI Anniversary',
                'date' => 'Aug 17, 2024',
                'status' => 'upcoming',
                'badge_color' => 'primary'
            ],
            [
                'title' => 'Talent Expo',
                'date' => 'Aug 24, 2024',
                'status' => 'registered',
                'badge_color' => 'success'
            ]
        ];

        return view('dashboard', compact('user', 'stats', 'recentActivities', 'upcomingEvents', 'totalRevenue'));
    }
    // Add this method to your DashboardController
public function adminDashboard()
{
    // Calculate total revenue from payments and donations
    $totalPayments = Payment::where('status', 'completed')->sum('amount');
    $totalDonations = MpesaDonation::where('status', 'completed')->sum('amount');
    $totalRevenue = $totalPayments + $totalDonations;

    return view('admin.dashboard', compact('totalRevenue'));
}
}