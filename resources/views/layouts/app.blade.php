<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Logbook Magang')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f5f5f9] font-sans text-slate-800 antialiased">

    <aside class="border-b border-white/10 bg-[#19182b] text-white lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-64 lg:flex-col lg:border-b-0">
        <div class="px-6 py-7 lg:px-7 lg:py-9">
            <a
                href="{{ route('activities.index') }}"
                class="flex items-center gap-3"
            >
                <span class="flex size-10 items-center justify-center rounded-2xl bg-violet-400 text-xl font-black text-[#19182b]">
                    L
                </span>

                <span class="text-xl font-bold tracking-tight">
                    logbook<span class="text-violet-400">.</span>
                </span>
            </a>

            <p class="mt-4 hidden text-xs leading-6 text-slate-400 lg:block">
                Catatan kecil hari ini.<br>
                Bekal untuk langkah berikutnya.
            </p>
        </div>

        <div class="px-4 pb-5 lg:px-5">
            <p class="mb-3 hidden px-3 text-[10px] font-semibold tracking-[0.2em] text-slate-500 lg:block">
                RUANG MAGANG
            </p>
            <a
    href="{{ route('learning.index') }}"
    @class([
        'flex flex-1 items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition lg:flex-none',
        'bg-violet-400/15 text-violet-200' => request()->routeIs('learning.*'),
        'text-slate-400 hover:bg-white/5 hover:text-white' => !request()->routeIs('learning.*'),
    ])
    @if(request()->routeIs('learning.*')) aria-current="page" @endif
>
    <svg
        class="size-5 shrink-0"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.7"
        aria-hidden="true"
    >
        <path d="M12 5v15"/>
        <path d="M12 5C9 3 5 3 3 4v15c3-1 6-1 9 1 3-2 6-2 9-1V4c-2-1-6-1-9 1Z"/>
    </svg>

    Pahami pekerjaanku
</a>


            <nav class="flex gap-2 lg:flex-col" aria-label="Navigasi utama">
                <a
                    href="{{ route('activities.index') }}"
                    @class([
                        'flex flex-1 items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition lg:flex-none',
                        'bg-violet-400/15 text-violet-200' => request()->routeIs('activities.*'),
                        'text-slate-400 hover:bg-white/5 hover:text-white' => !request()->routeIs('activities.*'),
                    ])
                    @if(request()->routeIs('activities.*')) aria-current="page" @endif
                >
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M12 20H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"/>
                        <path d="m16 3 5 5-9 9-6 1 1-6 9-9Z"/>
                    </svg>
                    Catatan harian
                </a>

                <a
                    href="{{ route('reports.weekly') }}"
                    @class([
                        'flex flex-1 items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition lg:flex-none',
                        'bg-violet-400/15 text-violet-200' => request()->routeIs('reports.*'),
                        'text-slate-400 hover:bg-white/5 hover:text-white' => !request()->routeIs('reports.*'),
                    ])
                    @if(request()->routeIs('reports.*')) aria-current="page" @endif
                >
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <rect x="4" y="3" width="16" height="18" rx="3"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>
                    Laporan mingguan
                </a>
            </nav>
        </div>

        <div class="mt-auto hidden px-7 pb-7 lg:block">
            <div class="border-t border-white/10 pt-5">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-full bg-white/10 text-xs font-semibold text-violet-200">
                        AA
                    </span>

                    <div>
                        <p class="text-sm font-medium">Alda Aunillah</p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Workspace pribadi
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div class="lg:pl-64">
        <header class="flex items-center justify-between border-b border-slate-200/70 bg-white/70 px-5 py-4 sm:px-8 lg:px-10">
            <p class="text-xs font-medium text-slate-500">
                Magang
                <span class="mx-2 text-slate-300">/</span>
                @yield('breadcrumb', 'Workspace')
            </p>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                {{ now('Asia/Jakarta')->locale('id')->translatedFormat('d M Y') }}
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
            @if(session('success'))
                <div role="status" class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div role="alert" class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm leading-7 break-words text-amber-900">
                    {{ session('warning') }}
                </div>
            @endif

            @if($errors->any())
                <div role="alert" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('submit', function (event) {
            const form = event.target;

            if (event.defaultPrevented || !form.matches('[data-loading]')) {
                return;
            }

            const button = form.querySelector('button[type="submit"]');

            if (button) {
                button.disabled = true;
                button.textContent = form.dataset.loading;
            }
        });
    </script>
</body>
</html>