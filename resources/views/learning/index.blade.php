@extends('layouts.app')

@section('title', 'Pahami Pekerjaanku — Logbook')
@section('breadcrumb', 'Pahami pekerjaanku')

@section('content')
    <div class="mb-8">
        <p class="mb-2 text-xs font-semibold tracking-[0.18em] text-violet-600">
            MY LEARNING SPACE
        </p>

        <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
            Kerjakan. Pahami. Ceritakan.
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">
            Kenali tujuan dan proses di balik pekerjaanmu.
            Kumpulkan bekal untuk menjelaskan pengalaman magang
            dengan bahasamu sendiri.
        </p>
    </div>

    <div class="mb-8 rounded-2xl border border-violet-100 bg-violet-50 px-5 py-4">
        <p class="text-sm font-medium text-violet-900">
            Ruang belajar pribadi
        </p>

        <p class="mt-1 text-xs leading-6 text-violet-700">
            Penjelasan ini terpisah dari laporan Word.
            Gambaran proses umum bukan berarti seluruh langkahnya
            sudah kamu kerjakan.
        </p>
    </div>

    <div class="space-y-6">
        @forelse($activities as $activity)
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                    <h2 class="text-sm font-semibold text-slate-700">
                        {{ $activity->activity_date->locale('id')->translatedFormat('l, d F Y') }}
                    </h2>

                    @if(filled($activity->learning_note))
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-medium text-emerald-700">
                            Bahan belajar tersedia
                        </span>
                    @else
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-500">
                            Belum dibuatkan penjelasan
                        </span>
                    @endif
                </div>

                <div class="p-6 sm:p-7">
                    <p class="mb-2 text-[10px] font-semibold tracking-[0.16em] text-slate-400">
                        KEGIATAN YANG KAMU CATAT
                    </p>

                    <p class="text-sm leading-7 whitespace-pre-wrap break-words text-slate-700">{{ $activity->raw_note }}</p>

                    @if(filled($activity->learning_note))
                        <details class="group mt-6" open>
                            <summary class="cursor-pointer text-sm font-semibold text-violet-700">
                                Baca penjelasan pekerjaan
                            </summary>

                            <div class="mt-4 rounded-2xl border border-slate-100 bg-[#fafafd] p-5 sm:p-7">
                                <p class="text-sm leading-8 whitespace-pre-wrap break-words text-slate-600">{{ $activity->learning_note }}</p>
                            </div>
                        </details>
                    @else
                        <form
                            method="POST"
                            action="{{ route('learning.generate', $activity) }}"
                            data-loading="Sedang menyusun penjelasan…"
                            class="mt-5"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700 disabled:cursor-wait disabled:opacity-60"
                            >
                                <span aria-hidden="true">✦</span>
                                Jelaskan pekerjaan ini
                            </button>

                            <p class="mt-3 text-xs leading-6 text-slate-400">
                                Dibuat sekali, lalu disimpan untuk dibaca kembali.
                            </p>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <h2 class="text-lg font-semibold text-slate-700">
                    Belum ada kegiatan untuk dipelajari.
                </h2>

                <p class="mt-2 text-sm text-slate-400">
                    Mulai dengan mencatat pekerjaan pertamamu.
                </p>

                <a
                    href="{{ route('activities.index') }}"
                    class="mt-5 inline-block text-sm font-semibold text-violet-600"
                >
                    Tulis kegiatan →
                </a>
            </div>
        @endforelse
    </div>

    @if($activities->hasPages())
        <nav class="mt-6 flex flex-wrap items-center justify-between gap-4" aria-label="Halaman bahan belajar">
            <span class="text-xs text-slate-500">
                Halaman {{ $activities->currentPage() }}
                dari {{ $activities->lastPage() }}
            </span>

            <div class="flex gap-3">
                @if($activities->previousPageUrl())
                    <a
                        href="{{ $activities->previousPageUrl() }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-medium"
                    >
                        ← Sebelumnya
                    </a>
                @endif

                @if($activities->nextPageUrl())
                    <a
                        href="{{ $activities->nextPageUrl() }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-medium"
                    >
                        Berikutnya →
                    </a>
                @endif
            </div>
        </nav>
    @endif
@endsection