@extends('layouts.app')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('head')
    @include('partials.og-tags')
@stop

@section('content')
    <div class="mx-auto max-w-7xl">

        <h1 class="mt-8 mb-12 flex gap-2 md:gap-4 items-center">
            @if (!empty($tool->uploaded_favicon))
                <img class="h-10 w-10 md:h-14 md:w-14 bg-white shadow rounded-sm md:rounded-xl"
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

        <article class="prose max-w-screen-lg lg:prose-lg pt-12 pb-4">
            <h2>What is {{ $tool->name }}</h2>

            {!! nl2br($tool->description) !!}

            @if (!empty($tool->top_features))
                <h3>Features:</h3>
                <ul>
                    @foreach ($tool->top_features as $feature)
                        <li>{{ $feature }}</li>
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
@stop


@section('aside')
    @if (!empty($relatedTools))
        <div class="">
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
@stop
