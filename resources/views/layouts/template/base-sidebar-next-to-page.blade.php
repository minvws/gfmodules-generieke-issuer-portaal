<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '') }}</title>
    <link rel="preload" href="{{ asset('img/ro-logo.svg') }}" as="image">
    <link href="{{ asset('img/favicon.ico') }}" rel="shortcut icon">
    @stack('styles')
</head>
<body class="sidemenu">
    @hasSection('sidemenu')
        @yield('sidemenu')
    @endif

    <div class="page-content ziekenboeg">
        @yield('header')

        <main id="main-content" tabindex="-1">
            @if (session()->has('error'))
                <section role="alert" class="error no-print" aria-label="{{ __('error') }}">
                    <div>
                        <p><span>@lang('Error'):</span> {{ session('error') }} {{ session('error_description') }}</p>
                    </div>
                </section>
            @endif

            @yield('content')
        </main>

        @yield('footer')
    </div>

    @stack('scripts')
</body>
</html>
