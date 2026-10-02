@props([
    'name' => 'medical-cross',
])

@php
    $icon = $name ?: 'medical-cross';
@endphp

@switch($icon)
    @case('stethoscope')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3v4a6 6 0 0 0 12 0V3" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3v4a3 3 0 0 0 6 0V3M12 13v2a5 5 0 0 0 10 0v-1" />
            <circle cx="20" cy="11" r="2" stroke-width="1.8" />
        </svg>
        @break

    @case('tooth')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.2 3.5c1.5 0 2.7.8 4.8.8s3.3-.8 4.8-.8c2.8 0 4.7 2.3 4.1 5.2-.5 2.6-1.7 3.7-2.4 6.4-.8 3-1.3 5.4-3.1 5.4-1.5 0-1.5-4.7-3.4-4.7s-1.9 4.7-3.4 4.7c-1.8 0-2.3-2.4-3.1-5.4-.7-2.7-1.9-3.8-2.4-6.4-.6-2.9 1.3-5.2 4.1-5.2Z" />
        </svg>
        @break

    @case('mother-child')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <circle cx="9" cy="6" r="3" stroke-width="1.8" />
            <circle cx="17" cy="10" r="2" stroke-width="1.8" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.5 20c.4-4.7 2.2-7 5.5-7s5.1 2.3 5.5 7M14.5 20c.2-3 1-4.5 2.5-4.5S19.3 17 19.5 20" />
        </svg>
        @break

    @case('laboratory')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3h6M10 3v6l-5.2 8.2A2.5 2.5 0 0 0 6.9 21h10.2a2.5 2.5 0 0 0 2.1-3.8L14 9V3" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.7 16h8.6" />
        </svg>
        @break

    @case('physiotherapy')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <circle cx="12" cy="4.5" r="2.2" stroke-width="1.8" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10l5-2 5 2M12 8v5m0 0-4 7m4-7 4 7M8 12H4m12 0h4" />
        </svg>
        @break

    @case('acupuncture')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19 19 5M15.5 4.5l4 4M4 20l3-1-2-2-1 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 8h4M10 6v4" />
        </svg>
        @break

    @case('pharmacy')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10v4H7zM8 7h8l2 3v9a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-9l2-3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11v6m-3-3h6" />
        </svg>
        @break

    @case('health-check')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 4V2h6v2M8 12h8M12 8v8" />
        </svg>
        @break

    @case('vitamin')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3h8v4H8zM9 7h6l2 3v9a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v-9l2-3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11v6m-3-3h6" />
        </svg>
        @break

    @case('heart')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.8 5.8a5.4 5.4 0 0 0-7.6 0L12 7l-1.2-1.2a5.4 5.4 0 1 0-7.6 7.6L12 22l8.8-8.6a5.4 5.4 0 0 0 0-7.6Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.5 12h3l1.3-2.5 2.2 5 1.2-2.5h3.3" />
        </svg>
        @break

    @case('clinic')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21V8l8-5 8 5v13M8 21v-5h8v5" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v5m-2.5-2.5h5" />
        </svg>
        @break

    @default
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12M6 12h12" />
        </svg>
@endswitch
