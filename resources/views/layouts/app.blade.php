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
</head>

<body>

    @include('partials.navbar')

    <div class="w-full flex flex-col gap-4 px-3 md:px-16 lg:px-28 md:flex-row font-sans">

        <main class="md:w-2/3 lg:w-3/4">
            @yield('content')
        </main>

        <aside class="md:w-1/3 lg:w-1/4 py-4">
            @yield('aside')
        </aside>

    </div>

    @include('partials.footer')

    @yield('scripts')

    @vite(['resources/js/app.js'])

</body>

</html>
