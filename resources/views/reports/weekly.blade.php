\@extends('layouts.app')

@section('title', 'Laporan Mingguan — Logbook')
@section('breadcrumb', 'Laporan mingguan')

@section('content')
<div class="mb-6 flex justify-end">
    <a
        href="{{ route('reports.weekly.word') }}"
        class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700"
    >
        <svg
            class="size-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            aria-hidden="true"
        >
            <path d="M12 3v12m-5-5 5 5 5-5"/>
            <path d="M5 16v4h14v-4"/>
        </svg>

        Unduh Word · Draf
    </a>
</div>
    @php
        $totalNotes = collect($days)->sum(
            fn ($day) => $day['activities']->count()
        );

        $polishedNotes = collect($days)->sum(
            fn ($day) => $day['activities']
                ->filter(fn ($activity) => filled($activity->report_note))
                ->count()
        );
    @endphp

    <div class="mb-8">
        <p class="mb-2 text-xs font-semibold tracking-[0.18em] text-violet-600">
            WEEKLY JOURNAL
        </p>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                {{ $period['label'] }}
            </h1>

            <span class="rounded-full border border-violet-200 bg-violet-50 px-4 py-2 text-xs font-semibold text-violet-700">
                Pratinjau
            </span>
        </div>

        <p class="mt-3 text-sm text-slate-500">{{ $period['range'] }}</p>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-medium text-slate-400">Catatan minggu ini</p>
            <p class="mt-3 text-3xl font-bold tracking-tight">{{ $totalNotes }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-medium text-slate-400">Sudah dirapikan AI</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-emerald-600">{{ $polishedNotes }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-medium text-slate-400">Belum dirapikan</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-amber-600">{{ $totalNotes - $polishedNotes }}</p>
        </div>
    </div>

    <div class="space-y-5">
        @foreach($days as $day)
            <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                    <h2 class="text-sm font-semibold text-slate-800">
                        {{ $day['label'] }}
                    </h2>

                    <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-500">
                        {{ $day['activities']->count() }} catatan
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($day['activities'] as $activity)
                        <div class="p-6">
                            @if($activity->report_note)
                                <p class="mb-3 text-[10px] font-semibold tracking-[0.16em] text-violet-600">
                                    URAIAN LAPORAN
                                </p>

                                <p class="text-sm leading-8 whitespace-pre-wrap break-words text-slate-600">{{ $activity->report_note }}</p>
                            @else
                                <span class="mb-3 inline-block rounded-lg bg-amber-50 px-3 py-1 text-xs text-amber-700">
                                    Catatan asli · belum dirapikan
                                </span>

                                <p class="text-sm leading-8 whitespace-pre-wrap break-words text-slate-600">{{ $activity->raw_note }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="flex items-center gap-3 px-6 py-7 text-sm text-slate-400">
                            <span class="size-2 rounded-full bg-slate-200"></span>
                            Belum ada catatan untuk hari ini.
                        </div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

    <p class="mt-6 text-xs leading-6 text-slate-400">
        Hari tanpa catatan belum dianggap sebagai hari libur.
        Versi Word akan kita tambahkan pada tahap ekspor laporan.
    </p>
@endsection