<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Payment;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class AdminDashboardController extends Controller
{
    /**
     * نمایش داشبورد ادمین
     */
    public function index()
    {
        // === آمار کلی ===
        $totalUsers = User::count();
        $pendingMemberships = Profile::where('membership_status', 'pending')->count();
        $approvedPayments = Payment::where('status', 'approved')->count();
        $totalAmount = Payment::where('status', 'approved')->sum('amount');

        $stats = [
            'users' => $totalUsers,
            'pending_memberships' => $pendingMemberships,
            'approved_payments' => $approvedPayments,
            'total_amount' => $totalAmount,
        ];

        // === پرداخت‌های اخیر ===
        $latestPayments = Payment::with('user.profile')
            ->latest()
            ->take(5)
            ->get();

        // === کاربران جدید ===
        $latestUsers = User::with('profile')
            ->latest()
            ->take(5)
            ->get();

        // === داده‌های نمودار پرداخت‌ها (۱۲ ماه اخیر) ===
        $months = [];
        $values = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $label = Jalalian::fromCarbon($month)->format('Y/m');

            $sum = Payment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')
                ->sum('amount');

            $months[] = $label;
            $values[] = (int) $sum;
        }

        $chart = [
            'months' => $months,
            'values' => $values,
        ];

        return view('admin.dashboard', compact('stats', 'latestPayments', 'latestUsers', 'chart'));
    }
}
