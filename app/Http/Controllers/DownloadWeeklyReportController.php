<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\WeeklyReportService;
use Carbon\CarbonImmutable;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class DownloadWeeklyReportController extends Controller
{
    public function __invoke(WeeklyReportService $service)
    {
        $period = $service->period();

        $templatePath = storage_path(
            'app/templates/template_laporan_mingguan.docx'
        );

        if (! is_file($templatePath)) {
            return to_route('reports.weekly')->with(
                'warning',
                'Template Word belum ditemukan. Periksa folder storage/app/templates.'
            );
        }

        $activities = Activity::query()
            ->whereBetween('activity_date', [
                $period['start_date'],
                $period['end_date'],
            ])
            ->orderBy('activity_date')
            ->orderBy('id')
            ->get();

        if ($activities->isEmpty()) {
            return to_route('reports.weekly')->with(
                'warning',
                'Belum ada catatan pada minggu ini untuk diunduh.'
            );
        }

        if ($activities->contains(
            fn (Activity $activity) => blank($activity->report_note)
        )) {
            return to_route('reports.weekly')->with(
                'warning',
                'Ada catatan yang belum dirapikan. Klik Buat versi laporan pada catatan tersebut terlebih dahulu.'
            );
        }

        $grouped = $activities->groupBy(
            fn (Activity $activity) =>
                $activity->activity_date->toDateString()
        );

        $timezone = config('internship.timezone', 'Asia/Jakarta');

        $date = CarbonImmutable::parse(
            $period['start_date'],
            $timezone
        );

        $end = CarbonImmutable::parse(
            $period['end_date'],
            $timezone
        );

        $rows = [];

        while ($date->lessThanOrEqualTo($end)) {
            $dailyActivities = $grouped->get(
                $date->toDateString(),
                collect()
            );

            $dayName = mb_strtoupper(
                $date->locale('id')->translatedFormat('l'),
                'UTF-8'
            );

            if ($dailyActivities->isEmpty()) {
                $rows[] = [
                    'hari' => $dayName,
                    'kegiatan' => 'Belum ada catatan.',
                    'mulai' => '',
                    'selesai' => '',
                ];
            }

            foreach ($dailyActivities as $activity) {
                $time = $this->extractTime($activity->raw_note);

                $description = trim(
                    preg_replace(
                        '/\s+/u',
                        ' ',
                        $activity->report_note
                    ) ?? $activity->report_note
                );

                // Hilangkan awalan jam dari uraian jika sudah
                // dipindahkan ke kolom Mulai dan Selesai.
                if ($time['mulai'] !== '') {
                    $start = str_replace('.', '[.:]', $time['mulai']);
                    $finish = str_replace('.', '[.:]', $time['selesai']);

                    $description = preg_replace(
                        '/^(?:jam|pukul)?\s*'
                        . $start
                        . '\s*[–—-]\s*'
                        . $finish
                        . '\s*[:：]?\s*/iu',
                        '',
                        $description
                    ) ?? $description;
                }

                $rows[] = [
                    'hari' => $dayName,
                    'kegiatan' => $description,
                    'mulai' => $time['mulai'],
                    'selesai' => $time['selesai'],
                ];
            }

            $date = $date->addDay();
        }

        Settings::setOutputEscapingEnabled(true);

        $template = new TemplateProcessor($templatePath);

        $template->setValue('minggu', $period['week_number']);
        $template->setValue('periode', $period['range']);

        $template->cloneRowAndSetValues('hari', $rows);

        $temporaryFile = tempnam(
            sys_get_temp_dir(),
            'logbook_word_'
        );

        if ($temporaryFile === false) {
            throw new \RuntimeException(
                'Tidak dapat membuat file sementara untuk laporan.'
            );
        }

        try {
            $template->saveAs($temporaryFile);
        } catch (\Throwable $exception) {
            @unlink($temporaryFile);
            throw $exception;
        }

        $filename = 'Draf_Laporan_Minggu_'
            . $period['week_number']
            . '.docx';

        return response()
            ->download($temporaryFile, $filename, [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->deleteFileAfterSend(true);
    }

    private function extractTime(string $note): array
    {
        // Mengenali contoh:
        // Jam 07.30-10.00, pukul 08:00–10:00, jam 08-10.
        $pattern = '/(?:jam|pukul)\s*'
            . '([01]?\d|2[0-3])(?:[.:]([0-5]\d))?'
            . '\s*[–—-]\s*'
            . '([01]?\d|2[0-3])(?:[.:]([0-5]\d))?'
            . '(?![\d.:])/iu';

        $count = preg_match_all(
            $pattern,
            $note,
            $matches,
            PREG_SET_ORDER
        );

        // Jangan menebak jika ada beberapa rentang waktu
        // dalam satu catatan atau tidak ada jam yang dikenali.
        if ($count !== 1) {
            return ['mulai' => '', 'selesai' => ''];
        }

        $match = $matches[0];

        $start = sprintf(
            '%02d.%02d',
            (int) $match[1],
            (int) ($match[2] ?? 0)
        );

        $finish = sprintf(
            '%02d.%02d',
            (int) $match[3],
            (int) ($match[4] ?? 0)
        );

        if ($finish <= $start) {
            return ['mulai' => '', 'selesai' => ''];
        }

        return [
            'mulai' => $start,
            'selesai' => $finish,
        ];
    }
}