<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

class WeeklyReportService
{
    public function period(?string $date = null): array
    {
        $timezone = config('internship.timezone', 'Asia/Jakarta');

        $selectedDate = $date
            ? CarbonImmutable::parse($date, $timezone)->startOfDay()
            : CarbonImmutable::now($timezone)->startOfDay();

        $internshipStart = CarbonImmutable::parse(
            config('internship.start_date'),
            $timezone
        )->startOfDay();

        $firstMonday = CarbonImmutable::parse(
            config('internship.first_week_start'),
            $timezone
        )->startOfDay();

        if ($selectedDate->lessThan($internshipStart)) {
            throw new InvalidArgumentException(
                'Tanggal tersebut berada sebelum awal magang.'
            );
        }

        // Angka 1 berarti Senin.
        $monday = $selectedDate->startOfWeek(1);

        // Hitung selisih hari kalender, lalu ubah menjadi minggu.
        $days = (int) $firstMonday->diff($monday)->format('%r%a');

        $weekNumber = intdiv($days, 7) + 1;

        // Minggu pertama dimulai pada tanggal awal magang.
        $start = $monday->lessThan($internshipStart)
            ? $internshipStart
            : $monday;

        $end = $monday->addDays(4);

        return [
            'week_number' => $weekNumber,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'label' => 'Minggu ke-' . $weekNumber,
            'range' => $start->locale('id')->translatedFormat('d F Y')
                . ' s.d. '
                . $end->locale('id')->translatedFormat('d F Y'),
        ];
    }
}