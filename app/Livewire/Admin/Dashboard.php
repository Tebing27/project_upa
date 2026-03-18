<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use App\Models\Certificate;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $monthlyRegistrations = Registration::where('status', '!=', 'draft', 'and')
            ->whereMonth('created_at', '=', now()->month, 'and')
            ->whereYear('created_at', '=', now()->year, 'and')
            ->count('*');

        $waitingReview = Registration::where('status', '=', 'dokumen_ok', 'and')->count('*');

        $monthlyCertificates = Certificate::whereMonth('created_at', '=', now()->month, 'and')
            ->whereYear('created_at', '=', now()->year, 'and')
            ->count('*');

        $recentRegistrations = Registration::with(['user', 'scheme'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingSchedules = Registration::where('status', '=', 'terjadwal', 'and')
            ->select('exam_date', 'exam_location', 'scheme_id')
            ->selectRaw('count(*) as participant_count')
            ->with('scheme')
            ->groupBy('exam_date', 'exam_location', 'scheme_id')
            ->orderBy('exam_date')
            ->take(3)
            ->get();

        return view('livewire.admin.dashboard', [
            'monthlyRegistrations' => $monthlyRegistrations,
            'waitingReview' => $waitingReview,
            'monthlyCertificates' => $monthlyCertificates,
            'recentRegistrations' => $recentRegistrations,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }
}
