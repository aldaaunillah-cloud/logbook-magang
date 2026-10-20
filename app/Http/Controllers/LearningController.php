<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\GeminiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class LearningController extends Controller
{
    public function index(): View
    {
        $activities = Activity::query()
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->paginate(8);

        return view('learning.index', compact('activities'));
    }

    public function generate(
        Activity $activity,
        GeminiService $gemini
    ): RedirectResponse {
        if (filled($activity->learning_note)) {
            return back()->with(
                'success',
                'Penjelasan pekerjaan ini sudah tersedia.'
            );
        }

        try {
            // Pakai catatan asli sebagai sumber pengalaman.
            $explanation = $gemini->explain($activity->raw_note);
        } catch (Throwable $exception) {
            $message = $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Koneksi ke AI bermasalah. Coba kembali sebentar lagi.';

            return back()->with('warning', $message);
        }

        $activity->learning_note = $explanation;
        $activity->save();

        return back()->with(
            'success',
            'Penjelasan berhasil dibuat. Selamat belajar!'
        );
    }
}