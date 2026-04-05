<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Registration;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $monthlyRegistrations = Registration::query()
            ->where('status', '!=', 'draft')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $waitingReview = Registration::query()
            ->whereIn('status', ['menunggu_verifikasi', 'pending_payment'])
            ->count();

        $monthlyCertificates = Certificate::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $recentRegistrations = Registration::query()
            ->with(['user', 'scheme'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingSchedules = Registration::query()
            ->where('status', 'terjadwal')
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
