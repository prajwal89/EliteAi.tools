@extends('layouts.app-full-width')

@section('content')
    @include('partials.hero')

    <div class="px-2 md:px-8">

        {{-- category tools --}}
        @if (isset($category))
            <ul class="flex flex-col gap-4 w-full max-w-5xl mx-auto">
                @foreach ($category->tools as $cTool)
                    <li class="border flex flex-col sm:flex-row gap-2 rounded-lg shadow">
                        <img class="object-cover rounded-lg scale-95 border w-full sm:w-1/4"
                            src="{{ asset('/tools/' . $cTool->slug . '/screenshot.webp') }}" alt="{{ $cTool->name }}">

                        <div class="p-4">
                            <a class="text-xl sm:text-2xl hover:underline"
                                href="{{ route('tool.show', ['tool' => $cTool->slug]) }}">
                                <span class="font-bold"> {{ $cTool->name }}</span> - {{ $cTool->tag_line }}
                            </a>

                            <p class="text-gray-600 py-2">
                                {{ $cTool->summary }}
                            </p>

                            <button
                                class="flex items-center text-xs gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span>{{ $cTool->pricing_type }}</span>
                            </button>
                        </div>

                    </li>
                @endforeach
            </ul>
        @endif


        @if (isset($recentTools))
            <ul class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 p-2 xl:p-5">
                @foreach ($recentTools as $tool)
                    <li
                        class="relative bg-white flex flex-col justify-between border rounded shadow-md transition duration-500 hover:shadow-primary-400">

                        <a class="relative" href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
                            {{-- <svg class="absolute top-2 right-2 z-40 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                        </svg> --}}

                            <img class="rounded relative w-full object-cover"
                                src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}"
                                loading="lazy">
                        </a>

                        <ul class="flex flex-wrap justify-center px-2 my-2 text-sm gap-2">
                            @foreach ($tool->categories as $category)
                                @if ($loop->last)
                                    <li class="">
                                        {{-- <li class="bg-blue-100/50 px-4 py-0.5 rounded-full"> --}}
                                        <a href="{{ route('category.show', ['category' => $category->slug]) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @else
                                    <li class="border-r-2 border-gray-300 pr-2">
                                        {{-- <li class="bg-blue-100/50 px-4 py-0.5 rounded-full"> --}}
                                        <a href="{{ route('category.show', ['category' => $category->slug]) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                        <div class="flex flex-col justify-beetween gap-3 px-4 py-3">
                            <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}"
                                class="text-xl font-semibold text-gray-900 hover:text-primary-800">
                                {{ $tool->name }} - {{ $tool->tag_line }}
                            </a>

                            <ul class="flex flex-wrap justify-start my-1 text-xs gap-2">
                                <li class="flex items-center gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $tool->pricing_type }}</span>
                                </li>
                            </ul>

                            <p class="text-gray-700 break-all"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                {{ $tool->summary }}
                            </p>
                        </div>

                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@stop
