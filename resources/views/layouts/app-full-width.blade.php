<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @hasSection('description')
        <meta name="description" content="@yield('description')">
    @endif

    @hasSection('conical_url')
        <link rel="canonical" href="@yield('conical_url')">
    @endif

    @include('partials.favicon-tags')

    <title>@yield('title')</title>

    @vite(['resources/css/app.css'])

    @include('partials.analytics.google-analytics-tag')

    @include('partials.analytics.microsoft-clarity-analytics-tag')

    @yield('head')

    @if (isset($livewire) && $livewire == true)
        @livewireStyles
    @endif
</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @yield('scripts')

    @vite(['resources/js/app.js'])

    @if (isset($livewire) && $livewire == true)
        @livewireScripts
    @endif
</body>

</html>
