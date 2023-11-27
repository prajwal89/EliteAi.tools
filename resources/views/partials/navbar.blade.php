{{-- top navbar --}}
{{-- bg-primary-50 --}}
<nav id="main_navbar" style="transition: top 0.8s;"
    class="sticky top-0 z-50 px-4 py-2 flex bg-gray-50  gap-4 border-b-2 border-gray-400/30 justify-between items-center">

    <a href="/" class="shrink-0 flex gap-1">
        <div class="flex items-center gap-2">
            <img class="w-10" src="{{ asset('/images/logos/favicons/500.png') }}" alt="{{ config('app.name') }} logo">
            <p class="hidden md:block text-md md:text-xl font-bold text-black ">
                Elite <span class="text-primary-600">AI</span> Tools
            </p>
        </div>
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
                Elite <span class="text-primary-600">AI</span> Tools
            </a>

            <button class="navbar-close">
                <svg class="h-6 w-6 text-gray-400 cursor-pointer hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        {{-- @auth
            <a href="" class="flex justify-center items-center flex-row gap-4 mb-6">
                <img class="h-10 w-10 rounded-lg" src="{{ auth()->user()->avatar_url }}" alt="">
                <span class="text-lg">{{ auth()->user()->name }}</span>
            </a>
        @else
            <div class="flex justify-center gap-4 mb-6">
                <a class="btn-secondary" href="{{ route('auth.login') }}">
                    Register
                </a>
                <a class="btn-primary" href="{{ route('auth.login') }}">
                    Sign in
                </a>
            </div>
        @endauth --}}



        <div class="flex flex-col gap-4 p-2">
            <a href="{{ route('home') }}">
                Home
            </a>
            <a href="{{ route('search.show') }}">
                Search
            </a>
            <a href="{{ route('blog.index') }}">
                Blog
            </a>
            {{-- <a href="{{ route('search.popular.index') }}">
                Popular seaches
            </a> --}}
            <a class="btn-outline-primary" href="{{ route('tool.submit') }}">
                Submit tool
            </a>
        </div>


        <div class="mt-auto">

            <p class="my-4 text-xs text-center text-gray-400">
                <span>{{ config('app.name') }} Copyright © {{ date('Y') }}</span>
            </p>
        </div>

    </nav>
</div>
