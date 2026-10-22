<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d0d0e">
    <title>@yield('title', 'Logbook Magang')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-ink focus:p-3 focus:text-canvas">Langsung ke konten</a>
    <header class="shell">
        <div class="flex flex-wrap items-center justify-between gap-x-5 gap-y-4 border-b border-white/10 py-5 sm:py-6">
            <a href="{{ route('activities.index') }}" class="flex items-center gap-2.5 text-xl font-extrabold tracking-[-0.06em]" aria-label="Logbook, halaman catatan">
                <span class="text-accent"><x-icon name="spark" class="size-7" /></span>
                logbook
            </a>
            <nav aria-label="Navigasi utama" class="order-3 flex w-full justify-between gap-1 rounded-full border border-white/10 p-1 sm:order-none sm:w-auto">
                <a href="{{ route('activities.index') }}" class="nav-link flex-1" @if(request()->routeIs('activities.*')) aria-current="page" @endif><span class="hidden opacity-50 lg:inline">01</span> Catatan</a>
                <a href="{{ route('reports.weekly') }}" class="nav-link flex-1" @if(request()->routeIs('reports.*')) aria-current="page" @endif><span class="hidden opacity-50 lg:inline">02</span> Laporan</a>
                <a href="{{ route('learning.index') }}" class="nav-link flex-1 whitespace-nowrap" @if(request()->routeIs('learning.*')) aria-current="page" @endif><span class="hidden opacity-50 lg:inline">03</span> Ruang belajar</a>
            </nav>
            <div class="flex items-center gap-3">
                <div class="hidden text-right lg:block"><p class="text-xs font-semibold">Alda Aunillah</p><p class="mt-1 text-[10px] text-muted">Jurnal magang pribadi</p></div>
                <span class="flex size-10 items-center justify-center rounded-full border border-accent/30 bg-accent/10 text-xs font-bold text-accent" aria-label="Alda Aunillah">AA</span>
            </div>
        </div>
    </header>
    <main id="main-content" class="shell pt-6 pb-12 sm:pt-8 sm:pb-20">
        @if(session('success'))
            <div role="status" class="alert border-emerald-400/25 bg-emerald-400/5 text-emerald-200">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div role="alert" class="alert border-amber-400/25 bg-amber-400/5 text-amber-200">{{ session('warning') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="alert border-red-400/25 bg-red-400/5 text-red-200">
                <p class="mb-1 font-semibold">Sebentar, periksa isianmu dulu.</p>
                <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
    <footer class="shell">
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-white/10 py-7 text-[10px] text-muted">
            <p class="flex items-center gap-2"><x-icon name="spark" class="size-4 text-accent" /> Catatan hari ini. Bekal untuk nanti.</p>
            <p>LOGBOOK / {{ now('Asia/Jakarta')->year }}</p>
        </div>
    </footer>
</body>
</html>
