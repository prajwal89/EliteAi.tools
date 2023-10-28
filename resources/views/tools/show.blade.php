@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('head')
    @include('partials.og-tags')
@stop

@section('content')
    <div class="mx-auto max-w-3xl px-2">

        <div class="my-8">
            <h1 class="flex gap-2 md:gap-4 items-center mb-4">
                @if (!empty($tool->uploaded_favicon))
                    <img class="h-10 w-10 md:h-14 md:w-14 bg-white shadow rounded"
                        src="{{ asset('/tools/' . $tool->slug . '/favicon.webp') }}" alt="{{ $tool->name }} favicon">
                @endif
                <div>
                    <span class="font-bold text-2xl sm:text-3xl md:text-4xl">
                        {{ $tool->name }}
                        @if (isAdmin())
                            <a href="{{ route('admin.tools.edit', ['tool' => $tool->id]) }}">...</a>
                        @endif
                    </span>
                    <br>
                    <span class="font-semibold text-md md:text-xl text-gray-600">{{ $tool->tag_line }}</span>
                </div>
            </h1>
            {{-- <p class="text-lg">
                {{ $tool->summary }}
            </p> --}}
        </div>



        <div class="relative">
            <div aria-hidden="true" class="absolute -z-10 inset-0 h-max w-full m-auto opacity-40">
                <div class="blur-[106px] h-[200px] bg-gradient-to-br from-primary-500 to-purple-400"></div>
            </div>
            @if (!empty($tool->uploaded_screenshot))
                <img class="w-full mx-auto shadow-sm shadow-gray-200 rounded-2xl max-w-3xl border"
                    src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}">
            @endif
        </div>

        @include('partials.tools.tool-details')

        <article class="prose max-w-screen-lg lg:prose-lg py-4">

            <h2>What is {{ $tool->name }}</h2>

            @if (!empty($tool->yt_introduction_video_id))
                <div class="relative" style="padding-bottom: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full rounded-lg" width="560" height="315"
                        src="https://www.youtube.com/embed/{{ $tool->yt_introduction_video_id }}" frameborder="0"
                        allowfullscreen></iframe>
                </div>
            @endif

            <div>
                {!! $tool->description !!}
            </div>

            @if (!empty($tool->top_features))
                <h3>Features:</h3>
                <ul>
                    @foreach ($tool->top_features as $feature)
                        <li>
                            {{ $feature }}
                            {{-- <strong>{{ str($feature)->before(':') }}</strong> : {{ str($feature)->after(':') }} --}}
                        </li>
                    @endforeach
                </ul>
            @endif


            @if (!empty($tool->use_cases))
                <h3>Use cases:</h3>
                <ul>
                    @foreach ($tool->use_cases as $useCases)
                        <li>{{ $useCases }}</li>
                    @endforeach
                </ul>
            @endif

        </article>

        <div class="flex justify-center py-8">
            <a class="px-4 py-2 border border-primary-600 bg-primary-50 hover:bg-primary-100 text-lg md:text-xl rounded"
                href="{{ $tool->home_page_url }}" target="_blank">
                Visit website
            </a>
        </div>

    </div>


    <div class="max-w-6xl mx-auto my-8 px-2">
        <div class="flex justify-center relative">
            <span class="text-2xl md:text-3xl font-bold">
                Related Tools
            </span>
        </div>
        <ul class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 p-2 xl:p-5">
            @foreach ($relatedTools as $vTool)
                <x-tool-cards.square-card :tool="$vTool" />
            @endforeach
        </ul>

        <div class="flex justify-center py-8">
            <a class="px-4 py-2 border border-primary-600 bg-primary-50 hover:bg-primary-100 text-lg md:text-xl rounded"
                href="{{ route('tool.alternatives', ['tool' => $tool->slug]) }}">
                View all related tools
            </a>
        </div>
    </div>
@stop


{{-- @section('aside')
    @if (!empty($relatedTools))
        <div class="my-4 md:my-8">
            <div class="flex justify-center relative">
                <span class="text-xl md:text-xl font-bold">
                    Categories
                </span>
            </div>
            <ul class="flex gap-2 my-1 flex-wrap justify-center p-2">
                @foreach ($categories as $c)
                    <li
                        class="px-2 py-1 md:text-lg relative bg-gray-100 rounded-lg select-none cursor-pointer hover:shadow hover:shadow-primary-500 hover:outline hover:outline-primary-600">
                        <a href="{{ route('category.show', ['category' => $c->slug]) }}">
                            {{ $c->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>



        <div class="md:hidden">
            <div class="flex justify-center relative">
                <span class="text-2xl md:text-3xl font-bold">
                    Related Tools
                </span>
            </div>
            <ul class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 p-2 xl:p-5 ">
                @foreach ($relatedTools as $vTool)
                    <x-tool-card.square :tool="$vTool" />
                @endforeach
            </ul>
        </div>

        <div class="hidden md:block">
            <h3 class="font-semibold">Related Tools</h3>
            <ul class="flex flex-col gap-2 p-2">
                @foreach ($relatedTools as $relatedTool)
                    <li class="border rounded-lg hover:shadow px-2 py-1">
                        <a class="text-lg font-semibold hover:underline"
                            href="{{ route('tool.show', ['tool' => $relatedTool->slug]) }}">{{ $relatedTool->name }}</a>
                        <p>{{ truncate($relatedTool->summary, 72) }}</p>
                    </li>
                @endforeach
            </ul>
            <div class="flex justify-end px-2">
                <a class="text-primary-600" href="{{ route('tool.alternatives', ['tool' => $tool->slug]) }}">
                    View all
                </a>
            </div>
        </div>
    @endif
@stop --}}
