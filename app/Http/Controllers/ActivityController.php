<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\GeminiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ActivityController extends Controller
{
    public function index(): View
    {
        $activities = Activity::query()
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    public function store(
        Request $request,
        GeminiService $gemini
    ): RedirectResponse {
        $validated = $this->validateNote($request);

        // Simpan dahulu agar catatan tetap ada jika AI gagal.
        $activity = Activity::create([
            'activity_date' => $validated['activity_date'],
            'raw_note' => $validated['raw_note'],
        ]);

        return $this->generateReportNote(
            $activity,
            $gemini,
            'Catatan tersimpan dan versi laporan berhasil dibuat.'
        );
    }

    public function update(
        Request $request,
        Activity $activity,
        GeminiService $gemini
    ): RedirectResponse {
        $validated = $this->validateNote($request);

        $activity->raw_note = $validated['raw_note'];

        if (! $activity->isDirty('raw_note')) {
            return to_route('activities.index')
                ->with('success', 'Tidak ada perubahan pada catatan.');
        }

        // Tanggal kegiatan tetap dipertahankan saat teks diedit.
        // Hasil AI lama dibersihkan karena sumbernya berubah.
        $activity->report_note = null;
        $activity->learning_note = null;
        $activity->save();

        return $this->generateReportNote(
            $activity,
            $gemini,
            'Catatan dan versi laporan berhasil diperbarui.'
        );
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return to_route('activities.index')
            ->with('success', 'Catatan berhasil dihapus.');
    }

    public function polish(
        Activity $activity,
        GeminiService $gemini
    ): RedirectResponse {
        return $this->generateReportNote(
            $activity,
            $gemini,
            'Versi laporan berhasil dibuat.'
        );
    }

    private function validateNote(Request $request): array
    {
        $request->merge([
            'raw_note' => trim(
                (string) $request->input('raw_note', '')
            ),
        ]);

        $rules = [
            'raw_note' => ['required', 'string', 'max:10000'],
        ];

        if ($request->routeIs('activities.store')) {
            $rules['activity_date'] = [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:' . config('internship.start_date'),
                'before_or_equal:' . now('Asia/Jakarta')->toDateString(),
            ];
        }

        return $request->validate($rules, [
            'raw_note.required' => 'Tulis kegiatanmu terlebih dahulu.',
            'raw_note.max' => 'Catatan maksimal 10.000 karakter.',

            'activity_date.required' => 'Pilih tanggal kegiatan.',
            'activity_date.date_format' => 'Format tanggal tidak valid.',
            'activity_date.after_or_equal' =>
                'Tanggal tidak boleh sebelum awal magang.',
            'activity_date.before_or_equal' =>
                'Tanggal tidak boleh melewati hari ini.',
        ]);
    }

    private function generateReportNote(
        Activity $activity,
        GeminiService $gemini,
        string $successMessage
    ): RedirectResponse {
        try {
            $result = $gemini->polish($activity->raw_note);
        } catch (Throwable $exception) {
            $message = 'Catatan asli tetap tersimpan, tetapi '
                . 'perapian AI belum berhasil.';

            if ($exception instanceof \RuntimeException) {
                $message .= ' ' . $exception->getMessage();
            } else {
                $message .= ' Koneksi ke layanan AI bermasalah. '
                    . 'Coba kembali sebentar lagi.';
            }

            return to_route('activities.index')
                ->with('warning', $message);
        }

        $activity->report_note = $result;
        $activity->save();

        return to_route('activities.index')
            ->with('success', $successMessage);
    }
}