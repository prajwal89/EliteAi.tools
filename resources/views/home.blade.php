@extends('layouts.app')

@section('content')
    <div
        class="relative h-[400px] bg-gradient-to-tr from-primary-600 via-primary-700 to-violet-800 dark:bg-gradient-to-tb dark:from-black dark:via-gray-900 dark:to-black">
        <div id="particles-js" class="h-full w-full absolute">
            <canvas class="particles-js-canvas-el" style="width: 100%; height: 100%;" width="522" height="500">
            </canvas>
        </div>
        <div class="flex flex-col gap-4 justify-center items-center w-full h-full px-3 md:px-0 select-none">

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white">
                McqMate
            </h1>
            <p class="text-gray-300">
                MCQ Portal for Students, From Students
            </p>

            <div class="relative p-3 border border-gray-200 rounded-lg w-full max-w-lg">
                <form action="https://mcqmate.com/search">

                    <input dusk="hero_search" type="text" class="rounded-md w-full p-3 pr-10 " name="term"
                        placeholder="Search MCQ | Topic | Course">
                    <button type="submit" class="absolute right-6 top-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div>
        <ul class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 p-2 xl:p-5">
            @foreach ($tools as $tool)
                <li
                    class="relative bg-white flex flex-col justify-between border rounded-md shadow-md dark:bg-transparent dark:border-gray-700 transition duration-500 hover:shadow-primary-400">

                    <a class="relative" href="https://tailwindflex.com/mereani/hero-section-with-big-heading">
                        {{-- <svg class="absolute top-2 right-2 z-40 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                        </svg> --}}

                        <img class="rounded-md relative w-full object-cover"
                            src="{{ asset('/tool/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}"
                            loading="lazy">
                    </a>

                    <ul class="flex flex-wrap justify-center px-2 my-2 text-sm gap-2">
                        @foreach ($tool->categories as $category)
                            @if ($loop->last)
                                <li class="">
                                    {{-- <li class="bg-blue-100/50 px-4 py-0.5 rounded-full"> --}}
                                    <a href="{{ route('categories.show', ['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @else
                                <li class="border-r-2 border-gray-300 pr-2">
                                    {{-- <li class="bg-blue-100/50 px-4 py-0.5 rounded-full"> --}}
                                    <a href="{{ route('categories.show', ['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <div class="flex flex-col justify-beetween gap-3 px-4 py-3">
                        <a href="{{ route('tools.show', ['tool' => $tool->slug]) }}"
                            class="text-xl font-semibold text-gray-900 dark:text-white hover:text-primary-800 dark:hover:text-primary-300">
                            {{ $tool->name }} - {{ $tool->tag_line }}
                        </a>

                        <ul class="flex flex-wrap justify-start my-1 text-xs gap-2">
                            <li class="flex gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $tool->pricing_type }}</span>
                            </li>
                        </ul>

                        <p class="text-gray-700 dark:text-gray-300 break-all"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            {{ $tool->summary }}
                        </p>
                    </div>

                </li>
            @endforeach
        </ul>
    </div>
@stop


@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS.load('particles-js', '{{ asset('particlesjs-config.json') }}', function() {
            console.log('callback - particles.js config loaded');
        });
    </script> --}}
@stop
