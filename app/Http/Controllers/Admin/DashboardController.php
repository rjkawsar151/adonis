<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');

        $stats = [
            'total_services' => Service::count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
            'contacted_appointments' => Appointment::where('status', 'contacted')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
            'total_faqs' => Faq::count(),
        ];

        // Today's appointments
        $todayAppointments = Appointment::with('service')
            ->whereDate('preferred_date', $today)
            ->orderBy('preferred_time')
            ->get();

        // Weekly trend (last 7 days)
        $weeklyTrend = Appointment::select(
            DB::raw('DATE(preferred_date) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('preferred_date', '>=', now()->subDays(6)->format('Y-m-d'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing days
        $trendLabels = [];
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = now()->subDays($i)->format('D');
            $trendData[] = (int) ($weeklyTrend[$date]->total ?? 0);
        }

        // Status distribution
        $statusDistribution = [
            'pending' => $stats['pending_appointments'],
            'contacted' => $stats['contacted_appointments'],
            'confirmed' => $stats['confirmed_appointments'],
            'completed' => $stats['completed_appointments'],
            'cancelled' => $stats['cancelled_appointments'],
        ];

        // Recent appointments (last 10)
        $recentAppointments = Appointment::with('service')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Branch-wise bookings count (if available)
        $branchBreakdown = [
            'gulshan' => 0,
            'bashundhara' => 0,
        ];
        try {
            $branchCounts = DB::table('appointments')
                ->select('branch_id', DB::raw('COUNT(*) as total'))
                ->whereNotNull('branch_id')
                ->groupBy('branch_id')
                ->get();
            foreach ($branchCounts as $bc) {
                $branchBreakdown[$bc->branch_id] = (int) $bc->total;
            }
        } catch (\Throwable $e) {
            // branch_id column may not exist
        }

        // Admin user
        $adminUser = Auth::user();

        return view('admin.dashboard', compact(
            'stats',
            'todayAppointments',
            'trendLabels',
            'trendData',
            'statusDistribution',
            'recentAppointments',
            'branchBreakdown',
            'adminUser'
        ));
    }
}
