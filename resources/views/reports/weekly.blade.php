@extends('layouts.app')
@section('title', 'Laporan Mingguan — Logbook')
@section('content')
    @php
        $totalNotes = collect($days)->sum(fn ($day) => $day['activities']->count());
        $polishedNotes = collect($days)->sum(fn ($day) => $day['activities']->filter(fn ($activity) => filled($activity->report_note))->count());
        $recordedDays = collect($days)->filter(fn ($day) => $day['activities']->isNotEmpty())->count();
    @endphp
    <section class="journal-hero reveal relative overflow-hidden rounded-[28px] px-6 py-8 sm:px-10 sm:py-10 lg:px-12">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-4"><p class="eyebrow text-[#491609]">02 / The weekly edit</p><span class="rounded-full border border-[#491609]/30 px-3 py-1.5 text-[10px] font-semibold text-[#491609]">REKAP MINGGU BERJALAN</span></div>
        <div class="relative z-10 mt-8 flex flex-wrap items-end justify-between gap-8">
            <div><h1 class="text-[clamp(2.5rem,6vw,5rem)] leading-[1.08] font-extrabold tracking-[-0.065em] text-[#fff6e9]">{{ $period['label'] }}</h1><p class="mt-4 text-xs font-semibold text-[#481709] sm:text-sm">{{ $period['range'] }}</p><p class="mt-3 max-w-lg text-xs leading-6 text-[#481709]">Kegiatan yang kamu catat, terkumpul menjadi satu cerita.</p></div>
            <a href="{{ route('reports.weekly.word') }}" class="button bg-[#18120f] text-ink hover:bg-black"><x-icon name="download" /> Unduh Word · Draf</a>
        </div>
        <span class="pointer-events-none absolute -top-16 right-8 text-[250px] leading-none font-extrabold tracking-[-0.1em] text-[#491609]/10 sm:text-[330px]" aria-hidden="true">{{ str_pad($period['week_number'], 2, '0', STR_PAD_LEFT) }}</span>
    </section>
    <div class="grid grid-cols-3 gap-3 border-b border-white/10 py-7 sm:gap-8 sm:px-5 sm:py-8">
        @foreach([[$totalNotes, 'Catatan minggu ini'], [$polishedNotes, 'Sudah dirapikan'], [$recordedDays . ' / ' . count($days), 'Hari terisi']] as [$value, $label])
            <div class="border-r border-white/10 pl-2 last:border-0 sm:pl-0"><p class="text-2xl font-bold tracking-[-0.05em] sm:text-4xl">{{ $value }}</p><p class="mt-2 text-[10px] text-muted sm:text-xs">{{ $label }}</p></div>
        @endforeach
    </div>
    <div class="mt-10 mb-7 flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow text-accent">One week, in focus</p><h2 class="mt-3 text-3xl font-bold tracking-[-0.045em]">Hari demi hari</h2></div><p class="max-w-xs text-xs leading-6 text-muted">Draf dapat diunduh setelah semua catatan minggu ini dirapikan. Isinya mengikuti kegiatan yang kamu simpan.</p></div>
    @if($totalNotes > $polishedNotes)
        <div class="alert border-accent/25 bg-accent/5 text-[#f2c0a9]">{{ $totalNotes - $polishedNotes }} catatan belum dirapikan. <a href="{{ route('activities.index') }}" class="font-bold underline underline-offset-4">Rapikan di halaman catatan</a> agar versi laporan siap digunakan.</div>
    @endif
    <div class="space-y-4">
        @foreach($days as $day)
            <details class="surface overflow-hidden" @if($day['activities']->isNotEmpty()) open @endif>
                <summary class="flex items-center justify-between gap-3 px-5 py-5 sm:px-7 sm:py-6">
                    <div class="flex items-center gap-4"><span class="hidden text-sm font-medium text-accent sm:inline">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h3 class="text-xs leading-6 font-semibold sm:text-sm">{{ $day['label'] }}</h3></div>
                    <div class="flex shrink-0 items-center gap-3"><span class="text-[10px] text-muted">{{ $day['activities']->count() }} catatan</span><x-icon name="chevron" class="details-arrow text-muted" /></div>
                </summary>
                <div class="divide-y divide-white/10 border-t border-white/10">
                    @forelse($day['activities'] as $activity)
                        <div class="px-5 py-6 sm:px-7 lg:pl-16">
                            <p class="eyebrow mb-4 flex items-center gap-2 {{ filled($activity->report_note) ? 'text-accent' : 'text-muted' }}"><x-icon name="{{ filled($activity->report_note) ? 'spark' : 'pen' }}" class="size-3.5" />{{ filled($activity->report_note) ? 'Uraian laporan' : 'Catatan asli · belum dirapikan' }}</p>
                            <p class="prose-note max-w-4xl">{{ filled($activity->report_note) ? $activity->report_note : $activity->raw_note }}</p>
                        </div>
                    @empty
                        <p class="px-5 py-7 text-xs leading-6 text-muted sm:px-7">Belum ada kegiatan tercatat untuk tanggal ini. <a href="{{ route('activities.index') }}#catatan-baru" class="text-ink underline underline-offset-4">Tambahkan catatan</a>.</p>
                    @endforelse
                </div>
            </details>
        @endforeach
    </div>
    <div class="mt-7 flex items-start gap-3 text-[11px] leading-6 text-muted"><x-icon name="document" class="mt-1" /><p>Periksa kembali uraian sebelum menggunakan laporan. Hari tanpa catatan belum dianggap sebagai hari libur.</p></div>
@endsection
