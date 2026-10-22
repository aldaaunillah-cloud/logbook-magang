@props(['name' => 'arrow'])
<svg {{ $attributes->class(['size-4 shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('spark')
            <path d="m12 2 2.7 7.3L22 12l-7.3 2.7L12 22l-2.7-7.3L2 12l7.3-2.7Z"/>
            @break
        @case('pen')
            <path d="m16 3 5 5-12 12-6 1 1-6Z"/><path d="m13 6 5 5"/>
            @break
        @case('book')
            <path d="M12 5v15M12 5C9 3 5 3 3 4v15c3-1 6-1 9 1 3-2 6-2 9-1V4c-2-1-6-1-9 1Z"/>
            @break
        @case('document')
            <path d="M14 3H5v18h14V8Zm0 0v5h5M8 12h8M8 16h5"/>
            @break
        @case('download')
            <path d="M12 3v12m-5-5 5 5 5-5M4 16v5h16v-5"/>
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="3"/><path d="M16 3v4M8 3v4M3 11h18M8 15h2M14 15h2"/>
            @break
        @case('check')
            <path d="m5 12 4 4L19 6"/>
            @break
        @case('chevron')
            <path d="m6 9 6 6 6-6"/>
            @break
        @case('trash')
            <path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7M14 10v7"/>
            @break
        @default
            <path d="M5 12h14m-6-6 6 6-6 6"/>
    @endswitch
</svg>
