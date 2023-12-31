@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('head')
    @include('partials.og-tags')
@stop

@section('content')
    {{-- <div class="bg-gradient-to-b w-full from-primary-50 to-white absolute h-[400px] -z-20"></div> --}}
    <div class="mx-auto max-w-7xl px-2 md:px-8 ">

        <div class="py-8">
            <h1 class="flex gap-2 md:gap-4 items-center mb-4">
                @if (!empty($tool->uploaded_favicon))
                    <img class="h-10 w-10 md:h-14 md:w-14 rounded"
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

        <div class="flex flex-col md:items-center justify-center md:flex-row gap-4">

            <div class="relative max-w-xl">
                <div aria-hidden="true" class="absolute -z-10 -inset-10 h-max w-full m-auto opacity-40">
                    <div class="blur-[106px] h-[320px] bg-gradient-to-br from-primary-500 to-purple-400"></div>
                </div>
                @if (!empty($tool->uploaded_screenshot))
                    <img class="w-full mx-auto shadow-sm shadow-gray-200 rounded-2xl max-w-3xl border  "
                        src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}">
                @endif
            </div>

            @include('partials.tools.tool-details')
        </div>

        <article class="prose max-w-screen-lg lg:prose-lg py-4 sm:py-8 px-2">

            <h2 class="sm:mt-4">What is {{ $tool->name }}?</h2>

            @if (!empty($tool->yt_introduction_video_id))
                <div class="relative mb-10" style="padding-bottom: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full rounded-lg" width="560" height="315"
                        src="https://www.youtube.com/embed/{{ $tool->yt_introduction_video_id }}" frameborder="0"
                        allowfullscreen></iframe>
                </div>
            @endif

            @if (!empty($tool->vimeo_introduction_video_id))
                <div style="padding:56.25% 0 0 0;position:relative;"><iframe
                        src="https://player.vimeo.com/video/{{ $tool->vimeo_introduction_video_id }}?h=1699409fe2&color=ef2200&byline=0&portrait=0"
                        style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                        allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
                <script src="https://player.vimeo.com/api/player.js"></script>
            @endif

            <div>
                {!! $tool->description !!}
            </div>

            @if (!empty($tool->getFormattedFeatures()))
                <h3>Features:</h3>
                <ul>
                    @foreach ($tool->getFormattedFeatures() as $feature)
                        <li>{!! $feature !!}</li>
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

        {{-- <div class="flex justify-center py-8">
            <a class="px-4 py-2 border border-primary-600 bg-primary-50 hover:bg-primary-100 text-lg md:text-xl rounded"
                href="{{ $tool->home_page_url }}" target="_blank">
                Visit website
            </a>
        </div> --}}

        {{-- @if (!$topSearches->isEmpty())
            <div>
                <p class="font-semibold text-lg">Related Queries:</p>
                <ul class="flex gap-2 my-2 flex-wrap">
                    @foreach ($topSearches as $topSearch)
                        <li>
                            <a class="bg-gray-100 px-2 py-1 rounded-lg hover:bg-purple-300" href="{{ route('popular.show', ['top_search' => $topSearch->top_search_slug]) }}">
                                {{ $topSearch->top_search_query }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        @if (!$topSearches->isEmpty() and isAdmin())
            <div>
                <p class="font-semibold text-lg">Related Queries:</p>
                <ul class="flex gap-2 my-2 flex-wrap">
                    @foreach ($topSearches as $topSearch)
                        <li>
                            <a class="bg-gray-100 px-2 py-1 rounded-lg hover:bg-purple-300"
                                href="{{ route('search.popular.show', ['top_search' => $topSearch->slug]) }}">
                                {{ $topSearch->query }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!$relatedBlogs->isEmpty())
            <div>
                <p class="font-semibold text-lg">Related Blogs:</p>
                <ul class="flex gap-2 my-2 flex-wrap">
                    @foreach ($relatedBlogs as $blog)
                        <li>
                            <a class="bg-gray-100 px-2 py-1 rounded-lg hover:bg-purple-300"
                                href="{{ route('blog.show', ['blog' => $blog->blog_slug]) }}">
                                {{ $blog->blog_title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>


    <div class="max-w-7xl mx-auto my-8 px-2">
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
                href="{{ route('tool.alternatives.show', ['tool' => $tool->slug]) }}">
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
