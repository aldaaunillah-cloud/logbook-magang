@extends('layouts.app')
@section('title', 'Ruang Belajar — Logbook')
@section('content')
    <section class="surface reveal relative overflow-hidden px-6 py-8 sm:px-10 sm:py-11 lg:px-12">
        <div class="relative z-10 max-w-2xl"><p class="eyebrow text-accent">03 / Your learning space</p><h1 class="mt-7 text-[clamp(1.9rem,9.75vw,4.8rem)] sm:text-[clamp(2.5rem,5.5vw,4.8rem)] leading-[1.08] font-extrabold tracking-[-0.065em]">Bukan sekadar<br><span class="text-accent">mengerjakan</span></h1><p class="mt-6 max-w-md text-sm leading-7 text-muted">Pahami tujuan, kenali proses, lalu ceritakan kembali dengan bahasamu. Bekal kecil untuk seminar magang nanti.</p><a href="#bahan-belajar" class="button button-light mt-7">Buka bahan belajar <x-icon name="book" /></a></div>
        <div class="learning-art pointer-events-none absolute -right-20 bottom-0 hidden h-full w-[480px] items-center justify-center opacity-70 md:flex lg:right-0" aria-hidden="true">
            <div class="relative h-64 w-64 rotate-[-14deg] rounded-[42px] border border-accent/50 bg-gradient-to-br from-[#ffad6c] via-[#ef622f] to-[#7b291b] shadow-[15px_24px_70px_#ff713f20]"><span class="absolute inset-5 rounded-[28px] border border-[#fff4e0]/40"></span><svg class="absolute inset-12 size-40 text-[#4a1c12]" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2"><path d="M50 24v55M50 24C36 15 19 16 9 21v54c16-6 29-4 41 4 12-8 25-10 41-4V21c-10-5-27-6-41 3Z"/><path d="M20 33c8-1 14 1 20 4m-20 8c8-1 14 1 20 4m20-12c6-3 12-5 20-4m-20 16c6-3 12-5 20-4"/></svg></div>
        </div>
    </section>
    <div class="grid gap-5 border-b border-white/10 py-7 md:grid-cols-[1fr_1.5fr]"><p class="flex items-center gap-3 text-sm font-semibold"><x-icon name="spark" class="text-accent" /> Untuk dipahami, bukan dihafal.</p><p class="text-xs leading-6 text-muted">Penjelasan belajar terpisah dari laporan Word. Gambaran proses umum membantu kamu belajar; sesuaikan dengan pekerjaan yang benar-benar kamu lakukan.</p></div>
    <section id="bahan-belajar" class="mt-10 sm:mt-12" aria-labelledby="learning-heading">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow text-accent">Make sense of your experience</p><h2 id="learning-heading" class="mt-3 text-3xl font-bold tracking-[-0.045em] sm:text-4xl">Dari kerja, jadi ilmu</h2></div><span class="pill text-muted">{{ $activities->total() }} kegiatan</span></div>
        <div class="space-y-5">
            @forelse($activities as $activity)
                <article class="surface overflow-hidden" id="learning-{{ $activity->id }}">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-5 py-4 sm:px-7"><h3 class="flex items-center gap-2 text-xs font-semibold"><x-icon name="calendar" class="text-muted" />{{ $activity->activity_date->locale('id')->translatedFormat('l, d F Y') }}</h3><span class="text-[10px] {{ filled($activity->learning_note) ? 'text-[#bbceac]' : 'text-muted' }}">{{ filled($activity->learning_note) ? 'Bahan belajar tersedia' : 'Belum dibuatkan penjelasan' }}</span></div>
                    <div class="p-5 sm:p-7"><p class="eyebrow mb-4 text-muted">Berawal dari catatanmu</p><p class="prose-note max-w-4xl">{{ $activity->raw_note }}</p>
                        @if(filled($activity->learning_note))
                            <details class="mt-6 border-t border-white/10 pt-2" open>
                                <summary class="flex min-h-12 items-center justify-between gap-4 text-sm font-semibold text-accent"><span class="flex items-center gap-2"><x-icon name="book" /> Di balik pekerjaan ini</span><x-icon name="chevron" class="details-arrow" /></summary>
                                <div class="mt-3 rounded-2xl bg-[#101011] p-5 sm:p-7"><p class="prose-note max-w-4xl">{{ $activity->learning_note }}</p></div>
                            </details>
                        @else
                            <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-5"><p class="max-w-md text-xs leading-6 text-muted">Apa tujuannya? Bagaimana prosesnya?<br>Yuk, kenali pekerjaan ini lebih dalam.</p><form method="POST" action="{{ route('learning.generate', $activity) }}" data-loading="Menyusun penjelasan…">@csrf<button type="submit" class="button button-primary"><x-icon name="spark" /> Jelaskan pekerjaan ini</button></form></div>
                            <p class="mt-4 text-[10px] text-muted">Dibuat sekali, disimpan untuk dibaca kembali.</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state"><x-icon name="book" class="mx-auto mb-5 size-8 text-accent" /><h3 class="text-lg font-bold">Pengalamanmu dimulai dari satu catatan.</h3><p class="mt-3 text-sm leading-7 text-muted">Catat kegiatan dulu, lalu gali pembelajarannya di sini.</p><a href="{{ route('activities.index') }}#catatan-baru" class="button button-primary mt-6">Tulis kegiatan <x-icon /></a></div>
            @endforelse
        </div>
        @include('partials.pagination', ['paginator' => $activities])
    </section>
@endsection
