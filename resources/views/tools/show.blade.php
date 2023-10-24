@extends('layouts.app')

@section('content')
    <div class="px-4 mx-auto max-w-7xl">

        <h1 class="mt-8 mb-12 flex gap-2 md:gap-4 items-center">
            @if (!empty($tool->uploaded_favicon))
                <img class="h-8 w-8 md:h-14 md:w-14 bg-white shadow rounded-sm md:rounded-xl"
                    src="{{ asset('/tool/' . $tool->slug . '/favicon.webp') }}" alt="{{ $tool->name }} favicon">
            @endif
            <div>
                <span class="font-bold text-2xl sm:text-3xl md:text-4xl">{{ $tool->name }}</span>
                <br>
                <span class="font-semibold text-md text-gray-600">{{ $tool->tag_line }}</span>
            </div>
        </h1>

        <div class="relative">
            <div aria-hidden="true" class="absolute -z-10 inset-0 h-max w-full m-auto opacity-40">
                <div class="blur-[106px] h-[200px] bg-gradient-to-br from-primary-500 to-purple-400"></div>
            </div>
            @if (!empty($tool->uploaded_screenshot))
                <img class="w-full mx-auto shadow-sm shadow-gray-200 rounded-2xl max-w-3xl border"
                    src="{{ asset('/tool/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}">
            @endif
        </div>

        {{-- border-2 border-gray-300 --}}
        <fieldset class="text-lg rounded-lg py-2 mt-8 dark:border-gray-600">
            <legend class="text-xl font-semibold dark:text-gray-300">
                Details
            </legend>

            <div class="px-2 py-2">
                <p class="flex gap-2 pb-4 text-sm">
                    <span>Pricing:</span>
                    <button class="flex items-center gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $tool->pricing_type }}</span>
                    </button>
                </p>
                <div class="flex gap-2 pb-4 text-sm">
                    <span>Categories:</span>
                    <ul class="flex flex-wrap justify-center pr-2 text-sm gap-2">
                        @foreach ($tool->categories as $category)
                            @if ($loop->last)
                                <li class="">
                                    <a href="{{ route('categories.show', ['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @else
                                <li class="border-r-2 border-gray-300 pr-2">
                                    <a href="{{ route('categories.show', ['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>



        </fieldset>

        <article class="prose max-w-screen-lg lg:prose-lg pt-12 pb-16">
            <h2>What is {{ $tool->name }}</h2>
            <p>{{ $tool->summary }}</p>

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
                            href="{{ route('tools.show', ['tool' => $relatedTool->slug]) }}">{{ $relatedTool->name }}</a>
                        <p>{{ truncate($relatedTool->summary, 72) }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@stop
