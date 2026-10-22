@extends('layouts.app')
@section('title', 'Catatan Harian — Logbook')
@section('content')
    @php($today = now('Asia/Jakarta')->locale('id'))
    <section class="journal-hero reveal relative overflow-hidden rounded-[28px] px-6 pt-7 pb-9 sm:px-10 sm:pt-9 sm:pb-11 lg:px-12" aria-labelledby="page-title">
        <div class="relative z-10 flex items-center justify-between gap-4">
            <p class="eyebrow text-[#491609]">A little progress, every day</p>
            <span class="hidden rounded-full border border-[#491609]/30 px-3 py-1 text-[10px] font-semibold text-[#491609] sm:inline">MY INTERNSHIP JOURNAL</span>
        </div>
        <div class="relative z-10 mt-8 max-w-2xl sm:mt-9">
            <h1 id="page-title" class="text-[clamp(2.35rem,11.5vw,5.8rem)] leading-[1.02] font-extrabold tracking-[-0.065em] text-[#fff6e9] sm:text-[clamp(3rem,6.3vw,5.8rem)]">Hari ini,<br>layak dicatat</h1>
            <p class="mt-5 max-w-sm text-xs leading-6 font-medium text-[#481709] sm:text-sm">Pekerjaan boleh berlalu.<br>Cerita dan pembelajarannya tetap tinggal.</p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="#catatan-baru" class="button bg-[#18120f] text-ink hover:bg-black">Tulis kegiatan <x-icon name="pen" /></a>
                <a href="{{ route('reports.weekly') }}" class="button border border-[#481709]/40 text-[#381108] hover:bg-white/15">Lihat laporan <x-icon /></a>
            </div>
        </div>
        <div class="pointer-events-none absolute -right-40 top-20 opacity-45 sm:-right-10 sm:top-24 sm:opacity-90 lg:right-20 lg:top-20" aria-hidden="true">
            <div class="hero-orb relative"><span class="orbit"></span><span class="orbit"></span><span class="orbit"></span></div>
        </div>
        <div class="absolute right-8 bottom-10 hidden text-right text-[#481709] lg:block"><p class="eyebrow">{{ $today->translatedFormat('l') }}</p><p class="mt-2 text-5xl font-extrabold tracking-[-0.06em]">{{ $today->format('d') }}<span class="ml-2 text-sm font-medium tracking-normal">/ {{ $today->translatedFormat('M Y') }}</span></p></div>
    </section>
    <div class="grid grid-cols-3 gap-3 border-b border-white/10 px-1 py-5 sm:gap-6 sm:px-5 sm:py-6">
        @foreach([['01', 'Ceritakan', 'Tulis dengan bahasamu.'], ['02', 'Rapikan', 'Jadi uraian laporan.'], ['03', 'Pahami', 'Bawa pulang ilmunya.']] as [$number, $title, $description])
            <div class="flex items-center gap-3 sm:gap-4"><span class="text-[10px] text-accent sm:text-xs">{{ $number }}</span><div><p class="text-[11px] font-semibold sm:text-sm">{{ $title }}</p><p class="mt-1 hidden text-[11px] text-muted sm:block">{{ $description }}</p></div></div>
        @endforeach
    </div>

    <div id="catatan-baru" class="mt-10 grid gap-5 lg:mt-12 lg:grid-cols-[minmax(0,1fr)_320px]">
        <section class="surface p-5 sm:p-8" aria-labelledby="new-note-heading">
            <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                <div><p class="eyebrow text-accent">01 / Catatan baru</p><h2 id="new-note-heading" class="mt-3 text-2xl font-bold tracking-[-0.045em] sm:text-3xl">Apa cerita hari ini?</h2></div>
                <span class="flex size-10 items-center justify-center rounded-full border border-white/10 text-muted"><x-icon name="pen" class="size-5" /></span>
            </div>
            <form method="POST" action="{{ route('activities.store') }}" data-loading="Menyimpan & merapikan…">
                @csrf
                <div class="mb-5 flex flex-wrap items-center gap-3">
                    <label for="activity_date" class="flex items-center gap-2 text-xs text-muted"><x-icon name="calendar" /> Tanggal kegiatan</label>
                    <input id="activity_date" name="activity_date" type="date" value="{{ old('activity_date', $today->toDateString()) }}" min="{{ config('internship.start_date') }}" max="{{ $today->toDateString() }}" required class="rounded-full border border-white/15 bg-[#101011] px-4 py-2 text-xs text-ink" aria-describedby="date-help">
                </div>
                <p id="date-help" class="mb-5 text-[11px] leading-5 text-muted">Tanggal hari ini sudah terisi. Ganti jika ingin mencatat kegiatan kemarin.</p>
                <label for="raw_note" class="sr-only">Ceritakan kegiatanmu</label>
                <textarea id="raw_note" name="raw_note" rows="4" maxlength="10000" required data-counter="note-count" class="field min-h-36" placeholder="Jam 07.00 aku bikin berita dari dokumentasi kegiatan, lalu bantu cek dokumen kontrak…">{{ old('raw_note') }}</textarea>
                <div class="mt-2 text-right text-[10px] text-muted" id="note-count" aria-live="off">0 / 10.000</div>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-5">
                    <p class="flex items-center gap-2 text-[11px] text-muted"><x-icon name="spark" class="text-accent" /> AI bantu merapikan bahasanya.</p>
                    <button type="submit" class="button button-primary">Simpan kegiatan <x-icon /></button>
                </div>
            </form>
        </section>
        <aside class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1" aria-label="Jelajahi logbook">
            <a href="{{ route('reports.weekly') }}" class="group relative flex flex-col justify-between overflow-hidden rounded-[24px] bg-[#e9e4d9] p-6 text-[#24211d] transition hover:bg-[#f7f3eb]">
                <div class="flex justify-between"><span class="eyebrow text-[#756b5d]">The weekly edit</span><x-icon name="document" class="size-5" /></div>
                <div class="mt-7 flex items-end justify-between gap-3"><div><h2 class="text-xl leading-tight font-bold tracking-[-0.04em]">Satu minggu<br>Satu cerita utuh</h2><p class="mt-3 text-[11px] leading-5 text-[#6a6258]">Baca rekap & unduh laporan Word.</p></div><span class="flex size-9 shrink-0 items-center justify-center rounded-full border border-black/20 transition group-hover:rotate-[-35deg]"><x-icon /></span></div>
            </a>
            <a href="{{ route('learning.index') }}" class="surface group flex flex-col justify-between p-6 transition hover:border-accent/40">
                <div class="flex justify-between"><span class="eyebrow text-muted">Go a little deeper</span><x-icon name="book" class="size-5 text-accent" /></div>
                <div class="mt-6 flex items-end justify-between gap-3"><div><h2 class="text-xl font-bold tracking-[-0.04em]">Kerja, lalu mengerti</h2><p class="mt-3 text-[11px] leading-5 text-muted">Tujuan, proses, dan bekal seminar.</p></div><x-icon class="text-accent transition group-hover:translate-x-1" /></div>
            </a>
        </aside>
    </div>

    <section class="mt-12 sm:mt-16" aria-labelledby="history-heading">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
            <div><p class="eyebrow text-accent">Your days, documented</p><h2 id="history-heading" class="mt-3 text-3xl font-bold tracking-[-0.045em] sm:text-4xl">Jejak kegiatanmu</h2></div>
            <span class="pill text-muted">{{ $activities->total() }} catatan tersimpan</span>
        </div>
        <div class="space-y-5">
            @forelse($activities as $activity)
                <article class="surface overflow-hidden" id="activity-{{ $activity->id }}">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-5 py-4 sm:px-7">
                        <h3 class="flex items-center gap-2 text-xs font-semibold"><x-icon name="calendar" class="text-muted" /> {{ $activity->activity_date->locale('id')->translatedFormat('l, d F Y') }}</h3>
                        @if(filled($activity->report_note))<span class="flex items-center gap-1.5 text-[10px] text-[#bbceac]"><x-icon name="check" class="size-3.5" /> Versi laporan tersedia</span>@else<span class="text-[10px] text-accent">Menunggu perapian</span>@endif
                    </div>
                    <div class="grid lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1.15fr)]">
                        <div class="p-5 sm:p-7"><p class="eyebrow mb-4 text-muted">Dengan bahasamu</p><p class="prose-note">{{ $activity->raw_note }}</p></div>
                        <div class="border-t border-white/10 bg-white/[0.015] p-5 sm:p-7 lg:border-t-0 lg:border-l">
                            <p class="eyebrow mb-4 flex items-center gap-2 text-accent"><x-icon name="spark" class="size-3.5" /> Uraian laporan</p>
                            @if(filled($activity->report_note))
                                <p class="prose-note">{{ $activity->report_note }}</p>
                            @else
                                <p class="text-xs leading-6 text-muted">Catatanmu sudah tersimpan. Coba rapikan lagi untuk membuat versi laporan.</p>
                                <form method="POST" action="{{ route('activities.polish', $activity) }}" data-loading="Sedang merapikan…" class="mt-4">@csrf<button type="submit" class="button button-quiet"><x-icon name="spark" /> Rapikan dengan AI</button></form>
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-white/10 px-5 sm:px-7">
                        <details class="group">
                            <summary class="flex min-h-13 items-center justify-between gap-4 text-xs font-semibold text-muted hover:text-ink"><span class="flex items-center gap-2"><x-icon name="pen" class="size-3.5" /> Edit catatan</span><x-icon name="chevron" class="details-arrow size-4" /></summary>
                            <form method="POST" action="{{ route('activities.update', $activity) }}" data-loading="Menyimpan perubahan…" class="pb-6">
                                @csrf @method('PATCH')
                                <label for="note-{{ $activity->id }}" class="mb-3 block text-xs text-muted">Perbaiki catatan · tanggal kegiatan tetap</label>
                                <textarea id="note-{{ $activity->id }}" name="raw_note" rows="4" maxlength="10000" required class="field">{{ $activity->raw_note }}</textarea>
                                <p class="mt-2 text-[11px] leading-5 text-muted">Perubahan catatan akan memperbarui uraian laporan dan menghapus penjelasan belajar lama.</p>
                                <button type="submit" class="button button-primary mt-4">Simpan perubahan <x-icon name="check" /></button>
                            </form>
                        </details>
                    </div>
                    <div class="flex items-center justify-between gap-3 border-t border-white/10 px-5 py-2 sm:px-7">
                        <a href="{{ route('learning.index') }}" class="text-link"><x-icon name="book" /> Buka ruang belajar <x-icon class="size-3" /></a>
                        <form method="POST" action="{{ route('activities.destroy', $activity) }}" onsubmit="return confirm('Hapus catatan ini beserta uraian laporan dan penjelasannya?');">@csrf @method('DELETE')<button type="submit" class="flex min-h-10 items-center gap-1.5 text-[11px] text-[#c98b7f] transition hover:text-red-300"><x-icon name="trash" class="size-3.5" /> Hapus</button></form>
                    </div>
                </article>
            @empty
                <div class="empty-state"><x-icon name="pen" class="mx-auto mb-5 size-8 text-accent" /><h3 class="text-lg font-bold">Halaman pertamamu masih kosong.</h3><p class="mt-3 text-sm text-muted">Mulai dari satu kegiatan kecil hari ini.</p><a href="#catatan-baru" class="button button-quiet mt-6">Tulis catatan pertama <x-icon /></a></div>
            @endforelse
        </div>
        @include('partials.pagination', ['paginator' => $activities])
    </section>
@endsection
