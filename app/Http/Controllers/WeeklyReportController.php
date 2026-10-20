<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\WeeklyReportService;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class WeeklyReportController extends Controller
{
    public function index(WeeklyReportService $service): View
    {
        $period = $service->period();

        $activities = Activity::query()
            ->whereBetween('activity_date', [
                $period['start_date'],
                $period['end_date'],
            ])
            ->orderBy('activity_date')
            ->orderBy('id')
            ->get()
            ->groupBy(
                fn (Activity $activity) =>
                    $activity->activity_date->toDateString()
            );

        $days = [];

        $date = CarbonImmutable::parse(
            $period['start_date'],
            config('internship.timezone', 'Asia/Jakarta')
        );

        $end = CarbonImmutable::parse(
            $period['end_date'],
            config('internship.timezone', 'Asia/Jakarta')
        );

        while ($date->lessThanOrEqualTo($end)) {
            $days[] = [
                'label' => $date->locale('id')
                    ->translatedFormat('l, d F Y'),

                'activities' => $activities->get(
                    $date->toDateString(),
                    collect()
                ),
            ];

            $date = $date->addDay();
        }

        return view('reports.weekly', compact('period', 'days'));
    }
}