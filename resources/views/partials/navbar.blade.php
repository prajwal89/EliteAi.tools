{{-- top navbar --}}
{{-- bg-primary-50 --}}
<nav id="main_navbar" style="transition: top 0.8s;"
    class="sticky top-0 z-50 px-4 py-2 flex bg-gray-50  gap-4 border-b-2 border-gray-400/30 justify-between items-center">

    <a href="/" class="shrink-0 flex gap-1">
        <div class="flex items-center gap-2">
            <img class="w-6 pt-1" src="{{ asset('/images/logos/logo-64x64.png') }}" alt="{{ config('app.name') }} logo">
            <p class="hidden md:block text-md md:text-xl font-bold text-black ">
                {{ config('app.name') }}
            </p>
        </div>
        <small class="text-gray-500 text-xs">beta</small>
    </a>

    <form action="{{ route('search.show') }}" class="relative w-[420px] bg-white">
        <input
            class="border bg-transparent w-full border-gray-200 h-10 pl-2 pr-10 rounded-lg dark:text-gray-50 dark:border-gray-600"
            type="search" value="{{ request()->input('q') ?? '' }}" name="q" placeholder="Search">

        <button type="submit" class="absolute right-0 top-0 mt-3 mr-4 text-gray-400 dark:text-gray-200">
            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 56.966 56.966"
                style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve">
                <path
                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z">
                </path>
            </svg>
        </button>
    </form>

    <div class="flex items-center gap-4">

        <a class="hidden md:block btn-outline-primary" href="{{ route('tool.submit') }}">
            Submit tool
        </a>

        <button class="md:hidden navbar-burger flex items-center text-primary-600" id="navbar_burger">
            <svg class="block h-8 w-8 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <title>Hamberger menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
            </svg>
        </button>

    </div>

</nav>


{{-- side bavbar --}}
<div class="navbar-menu relative z-50 hidden">
    <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-50"></div>
    <nav class="fixed bg-white top-0 right-0 bottom-0 flex flex-col w-5/6 max-w-sm py-4 px-6 border-x overflow-y-auto">

        <div class="flex items-center mb-8">

            <a class="mr-auto text-2xl font-bold text-black" href="/">
                {{ config('app.name') }}
            </a>

            <button class="navbar-close">
                <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        @auth
            <a href="" class="flex justify-center items-center flex-row gap-4 mb-6">
                <img class="h-10 w-10 rounded-lg" src="{{ auth()->user()->avatar_url }}" alt="">
                <span class="text-lg">{{ auth()->user()->name }}</span>
            </a>
        @else
            {{-- <div class="flex justify-center gap-4 mb-6">
                <a class="btn-secondary" href="{{ route('auth.login') }}">
                    Register
                </a>
                <a class="btn-primary" href="{{ route('auth.login') }}">
                    Sign in
                </a>
            </div> --}}
        @endauth


        <div class="flex flex-col gap-4">
            @auth
                {{-- <details class="group" open='true'>
                    <summary
                        class="flex items-center justify-between gap-2 p-2 font-medium marker:content-none hover:cursor-pointer">

                        <span class="flex gap-2">
                            <img class="w-6 h-6 rounded-lg" src="{{ auth()->user()->avatar_url }}" alt="">
                            <span>
                                {{ auth()->user()->name }}
                            </span>
                        </span>
                        <svg class="w-5 h-5 text-gray-500 transition group-open:rotate-90"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                            </path>
                        </svg>
                    </summary>

                    <article class="px-4 pb-4">
                        <ul class="flex flex-col gap-4 pl-2 mt-4">

                            <li class="flex gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                </svg>


                                <a href="{{ route('user.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="flex gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                                </svg>

                                <a href="{{ route('user.study-lists') }}">
                                    Study Lists
                                </a>
                            </li>



                            <li class="flex gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>


                                <a href="{{ route('user.contribution') }}">
                                    Your contribution
                                </a>
                            </li>

                            <li class="flex gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                <a href="{{ route('user.settings') }}">
                                    Settings
                                </a>
                            </li>


                            <form action="{{ route('auth.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 text-sm px-2 py-1 hover:bg-red-200 rounded-md">
                                    Log Out
                                </button>
                            </form>

                        </ul>
                    </article>
                </details> --}}
            @endauth
        </div>


        <div class="flex flex-col gap-4 p-2">
            <a href="">
                Popular Universities
            </a>
            <a href="">
                Popular Exams
            </a>
        </div>


        <div class="mt-auto">

            <p class="my-4 text-xs text-center text-gray-400">
                <span>{{ config('app.name') }} Copyright © {{ date('Y') }}</span>
            </p>
        </div>

    </nav>
</div>
