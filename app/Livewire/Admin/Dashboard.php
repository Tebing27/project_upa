<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Exam;
use App\Models\Registration;
use App\Models\Scheme;
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
            ->with(['user.mahasiswaProfile', 'user.umumProfile', 'scheme'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingSchedules = Exam::query()
            ->join('registrations', 'registrations.id', '=', 'exams.registration_id')
            ->whereNotNull('exam_date')
            ->where('exam_date', '>=', now())
            ->where('registrations.status', 'terjadwal')
            ->select('exams.exam_date', 'exams.exam_location')
            ->selectRaw('MIN(registrations.scheme_id) as scheme_id')
            ->selectRaw('COUNT(*) as participant_count')
            ->groupBy('exams.exam_date', 'exams.exam_location')
            ->orderBy('exams.exam_date')
            ->take(3)
            ->get()
            ->pipe(function ($schedules) {
                $schemes = Scheme::query()
                    ->whereIn('id', $schedules->pluck('scheme_id')->filter()->unique())
                    ->get()
                    ->keyBy('id');

                return $schedules->map(function ($schedule) use ($schemes) {
                    $schedule->scheme = $schemes->get($schedule->scheme_id);

                    return $schedule;
                });
            });

        return view('livewire.admin.dashboard', [
            'monthlyRegistrations' => $monthlyRegistrations,
            'waitingReview' => $waitingReview,
            'monthlyCertificates' => $monthlyCertificates,
            'recentRegistrations' => $recentRegistrations,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }
}
