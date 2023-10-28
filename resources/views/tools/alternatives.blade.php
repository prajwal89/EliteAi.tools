@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('head')
    @include('partials.og-tags')
@stop


@section('content')
    <div class="max-w-7xl px-2 mx-auto">

        <div class="flex justify-center my-8">
            <h1 class="font-bold text-2xl sm:text-3xl md:text-4xl">
                {{ $tool->name }} - Alternatives & Competitors
            </h1>
        </div>

        <div class="relative flex gap-2 md:gap-4 flex-col md:flex-row my-4 border shadow bg-gray-50 rounded-xl p-4">
            <div aria-hidden="true" class="absolute -z-10 inset-0 h-max w-full m-auto opacity-40">
                <div class="blur-[106px] h-[200px] bg-gradient-to-br from-primary-500 to-purple-400"></div>
            </div>
            @if (!empty($tool->uploaded_screenshot))
                <div class="w-2/5">
                    <img class="w-full shadow-sm shadow-gray-200 rounded-2xl border"
                        src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}">
                </div>
            @endif

            <div class="flex flex-col justify-between">

                <div>
                    <h2 class="font-bold text-xl sm:text-2xl md:text-3xl text-primary-800">
                        <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
                            {{ $tool->name }}
                        </a>
                    </h2>

                    <p>
                        {{ $tool->summary }}
                    </p>

                    <p class="py-2">
                        <span>Home page: </span>
                        <a class="text-primary-600" href="{{ $tool->home_page_url }}">{{ $tool->home_page_url }}</a>
                    </p>

                    <ul class="flex flex-wrap text-sm gap-2 py-2">
                        @foreach ($tool->categories as $category)
                            <li>
                                <a class="flex items-center gap-2 hover:text-primary-600"
                                    href="{{ route('category.show', ['category' => $category->slug]) }}">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    <span>{{ $category->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- <div class="flex justify-end gap-2 py-2">
                    <a class="px-2 py-1 md:px-4 md:py-2 text-sm md:text-md border border-primary-500 rounded-md hover:bg-primary-100"
                        href="{{ $tool->home_page_url }}">Visit
                        website
                    </a>
                    <a class="px-2 py-1 md:px-4 md:py-2 text-sm md:text-md border border-primary-500 rounded-md hover:bg-primary-100"
                        href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
                        Tool details
                    </a>
                </div> --}}

            </div>
        </div>


        <div class="w-full my-12">
            <p class="mx-auto font-bold text-2xl md:text-3xl flex justify-center">
                Alternative tools for {{ $tool->name }}
            </p>
            <ul class="flex flex-col gap-4 w-full max-w-5xl mx-auto my-4 ">
                @foreach ($alternativeTools as $cTool)
                    <x-tool-cards.horizontal-card :tool="$cTool" />
                @endforeach
            </ul>
        </div>

    </div>
@stop
