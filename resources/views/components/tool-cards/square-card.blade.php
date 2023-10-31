<li
    class="relative bg-white flex flex-col justify-between border rounded shadow-md transition duration-500 hover:shadow-primary-400">

    <a class="relative" href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
        {{-- <svg class="absolute top-2 right-2 z-40 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
        </svg> --}}
        @if (!empty($tool->uploaded_screenshot))
            <img class="rounded relative w-full object-cover aspect-video"
                src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}" loading="lazy">
        @endif
    </a>

    {{-- <ul class="flex flex-wrap justify-center px-2 my-2 text-sm gap-2">
        @foreach ($tool->categories as $category)
            @if ($loop->last)
                <li class="">
                    <a href="{{ route('category.show', ['category' => $category->slug]) }}">
                        {{ $category->name }}
                    </a>
                </li>
            @else
                <li class="border-r-2 border-gray-300 pr-2">
                    <a href="{{ route('category.show', ['category' => $category->slug]) }}">
                        {{ $category->name }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul> --}}

    <div class="flex flex-col justify-beetween gap-3 px-4 py-3">
        <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}"
            class="text-xl font-semibold text-primary-700 hover:text-primary-800 two-lines">
            {{ $tool->name }}
            {{-- - {{ $tool->tag_line }} --}}
        </a>

        <p class="text-gray-600 two-lines">
            {{ $tool->summary }}
        </p>

        <ul class="flex flex-wrap items-center justify-start my-1 text-sm gap-2">
            <li title="Pricing type"
                class="flex items-center cursor-pointer gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $tool->pricing_type }}</span>
            </li>

            @if ($tool->has_api)
                <li title="Support for API"
                    class="flex items-center cursor-pointer gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M17 9V12C17 14.7614 14.7614 17 12 17M7 9V12C7 14.7614 9.23858 17 12 17M12 17V21M8 3V6M16 3V6M5 9H19"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>API</span>
                </li>
            @endif
        </ul>

        <ul class="flex flex-wrap text-sm gap-2 my-1">
            @foreach ($tool->categories as $category)
                <li class="flex items-center gap-2 ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    <span>{{ $category->name }}</span>
                </li>
            @endforeach
        </ul>
    </div>

</li>
