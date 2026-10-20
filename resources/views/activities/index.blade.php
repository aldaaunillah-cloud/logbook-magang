@extends('layouts.app')

@section('title', 'Catatan Harian — Logbook')
@section('breadcrumb', 'Catatan harian')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-2 text-xs font-semibold tracking-[0.18em] text-violet-600">
                MY INTERNSHIP JOURNAL
            </p>

            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                Cerita kerjamu, tersimpan rapi.
            </h1>

            <p class="mt-3 text-sm leading-7 text-slate-500">
                Catat kegiatan hari ini atau lengkapi kegiatan sebelumnya.
            </p>
        </div>

        <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-medium text-slate-500">
            {{ $activities->total() }} catatan tersimpan
        </span>
    </div>

    <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
        <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-5 sm:px-7">
                <span class="flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <svg
                        class="size-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        aria-hidden="true"
                    >
                        <path d="m16 3 5 5-9 9-6 1 1-6 9-9Z"/>
                    </svg>
                </span>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Catatan baru
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-400">
                        Tulis dengan bahasamu sendiri.
                    </p>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('activities.store') }}"
                data-loading="Menyimpan dan merapikan…"
                class="p-6 sm:p-7"
            >
                @csrf

                <div class="mb-6">
                    <label
                        for="activity_date"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tanggal kegiatan
                    </label>

                    <input
                        type="date"
                        id="activity_date"
                        name="activity_date"
                        value="{{ old('activity_date', now('Asia/Jakarta')->toDateString()) }}"
                        min="{{ config('internship.start_date') }}"
                        max="{{ now('Asia/Jakarta')->toDateString() }}"
                        required
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-violet-400 focus:ring-4 focus:ring-violet-50 sm:w-auto"
                    >

                    <p class="mt-2 text-xs leading-6 text-slate-400">
                        Otomatis hari ini. Ubah tanggalnya jika mencatat kegiatan sebelumnya.
                    </p>
                </div>

                <label
                    for="raw_note"
                    class="mb-3 block text-sm font-medium text-slate-700"
                >
                    Apa yang kamu kerjakan?
                </label>

                <textarea
                    id="raw_note"
                    name="raw_note"
                    rows="6"
                    maxlength="10000"
                    required
                    placeholder="Jam 07.30–10.00 aku bikin berita Telkom dari dokumentasi kegiatan, lalu revisi sesuai arahan pembimbing…"
                    class="block w-full resize-y rounded-2xl border border-slate-200 bg-slate-50/70 p-5 text-sm leading-7 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-violet-400 focus:bg-white focus:ring-4 focus:ring-violet-50"
                >{{ old('raw_note') }}</textarea>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-4">
                    <p class="max-w-xs text-xs leading-6 text-slate-400">
                        Sertakan jam dan kegiatan. AI membantu merapikan bahasanya.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600 disabled:cursor-wait disabled:opacity-60"
                    >
                        Simpan kegiatan
                        <span aria-hidden="true">↗</span>
                    </button>
                </div>
            </form>
        </section>

        <aside class="space-y-5">
            <div class="relative overflow-hidden rounded-3xl bg-[#25223f] p-6 text-white">
                <div class="pointer-events-none absolute -top-12 -right-12 size-40 rounded-full bg-violet-400/15 blur-2xl"></div>

                <p class="relative text-[10px] font-semibold tracking-[0.18em] text-violet-300">
                    LAPORAN MAGANG
                </p>

                <h2 class="relative mt-4 text-xl font-semibold leading-8 tracking-tight">
                    Satu minggu,<br>satu cerita progres.
                </h2>

                <p class="relative mt-3 text-xs leading-6 text-slate-300">
                    Lihat kegiatan yang sudah terkumpul untuk laporan minggu berjalan.
                </p>

                <a
                    href="{{ route('reports.weekly') }}"
                    class="relative mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-200 hover:text-white"
                >
                    Buka rekap mingguan
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="rounded-3xl border border-violet-100 bg-violet-50/70 p-6">
                <p class="text-sm font-semibold text-violet-900">
                    Ada kegiatan yang terlupa?
                </p>

                <p class="mt-3 text-xs leading-6 text-violet-800/80">
                    Pilih tanggal saat pekerjaan dilakukan, lalu tulis kegiatannya.
                    Catatan akan tersimpan pada tanggal tersebut.
                </p>
            </div>
        </aside>
    </div>

    <section class="mt-10">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-semibold tracking-tight text-slate-900">
                Jejak kegiatan
            </h2>

            <span class="text-xs text-slate-400">
                Tanggal terbaru lebih dahulu
            </span>
        </div>

        <div class="space-y-5">
            @forelse($activities as $activity)
                <article class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="size-2 rounded-full bg-violet-400"></span>

                            <h3 class="text-sm font-semibold text-slate-700">
                                {{ $activity->activity_date->locale('id')->translatedFormat('l, d F Y') }}
                            </h3>
                        </div>

                        @if($activity->report_note)
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-medium text-emerald-700">
                                Sudah dirapikan
                            </span>
                        @else
                            <span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-medium text-amber-700">
                                Belum dirapikan
                            </span>
                        @endif
                    </div>

                    <div class="p-6">
                        <p class="mb-2 text-[10px] font-semibold tracking-[0.16em] text-slate-400">
                            CATATAN ASLI
                        </p>

                        <p class="text-sm leading-7 whitespace-pre-wrap break-words text-slate-600">{{ $activity->raw_note }}</p>

                        @if($activity->report_note)
                            <div class="mt-5 rounded-2xl border border-violet-100 bg-violet-50/50 p-5">
                                <p class="mb-3 flex items-center gap-2 text-xs font-semibold text-violet-700">
                                    <span aria-hidden="true">✦</span>
                                    Versi laporan
                                </p>

                                <p class="text-sm leading-8 whitespace-pre-wrap break-words text-slate-700">{{ $activity->report_note }}</p>
                            </div>
                        @else
                            <form
                                method="POST"
                                action="{{ route('activities.polish', $activity) }}"
                                data-loading="Sedang merapikan…"
                                class="mt-4"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="rounded-xl bg-violet-50 px-4 py-2.5 text-xs font-semibold text-violet-700 transition hover:bg-violet-100 disabled:cursor-wait disabled:opacity-60"
                                >
                                    ✦ Buat versi laporan
                                </button>
                            </form>
                        @endif

                        <div class="mt-5 border-t border-slate-100 pt-4">
                            <details>
                                <summary class="w-fit cursor-pointer text-xs font-semibold text-slate-500 transition hover:text-violet-600">
                                    Edit catatan
                                </summary>

                                <form
                                    method="POST"
                                    action="{{ route('activities.update', $activity) }}"
                                    data-loading="Menyimpan perubahan…"
                                    class="mt-4"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <p class="mb-3 text-xs text-slate-400">
                                        Tanggal kegiatan tetap
                                        {{ $activity->activity_date->format('d/m/Y') }}.
                                    </p>

                                    <label
                                        for="note-{{ $activity->id }}"
                                        class="mb-2 block text-xs font-medium text-slate-600"
                                    >
                                        Perbaiki catatan kegiatan
                                    </label>

                                    <textarea
                                        id="note-{{ $activity->id }}"
                                        name="raw_note"
                                        rows="4"
                                        maxlength="10000"
                                        required
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-50"
                                    >{{ $activity->raw_note }}</textarea>

                                    <button
                                        type="submit"
                                        class="mt-3 rounded-xl bg-violet-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-violet-700 disabled:opacity-60"
                                    >
                                        Simpan perubahan
                                    </button>
                                </form>
                            </details>

                            <form
                                method="POST"
                                action="{{ route('activities.destroy', $activity) }}"
                                onsubmit="return confirm('Hapus catatan ini? Tindakan ini tidak bisa dibatalkan.');"
                                class="mt-3"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-xs font-medium text-rose-500 transition hover:text-rose-700"
                                >
                                    Hapus catatan
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white/60 px-6 py-12 text-center">
                    <p class="text-lg font-semibold text-slate-700">
                        Cerita pertamamu dimulai di sini.
                    </p>

                    <p class="mt-2 text-sm text-slate-400">
                        Tulis satu kegiatan melalui kotak di atas.
                    </p>
                </div>
            @endforelse
        </div>

        @if($activities->hasPages())
            <nav
                class="mt-6 flex flex-wrap items-center justify-between gap-4"
                aria-label="Halaman catatan"
            >
                <span class="text-xs text-slate-500">
                    Halaman {{ $activities->currentPage() }}
                    dari {{ $activities->lastPage() }}
                </span>

                <div class="flex gap-2">
                    @if($activities->previousPageUrl())
                        <a
                            href="{{ $activities->previousPageUrl() }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-medium hover:border-violet-300"
                        >
                            ← Sebelumnya
                        </a>
                    @endif

                    @if($activities->nextPageUrl())
                        <a
                            href="{{ $activities->nextPageUrl() }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-medium hover:border-violet-300"
                        >
                            Berikutnya →
                        </a>
                    @endif
                </div>
            </nav>
        @endif
    </section>
@endsection