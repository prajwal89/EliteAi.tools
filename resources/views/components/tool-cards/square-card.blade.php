<li class="relative bg-white flex flex-col justify-between border rounded shadow-md hover:shadow-primary-400">

    {{-- <a class="relative" href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
        @if (!empty($tool->uploaded_screenshot))
            <img class="rounded relative w-full object-cover aspect-video"
                src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}" loading="lazy">
        @endif
    </a> --}}

    <a class="relative" href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
        @if (!empty($tool->uploaded_screenshot))
            <div class="relative w-full aspect-video">
                <img class="rounded w-full h-full object-cover"
                    src="{{ asset('/tools/' . $tool->slug . '/screenshot-large.webp') }}" alt="{{ $tool->name }}"
                    loading="lazy">

                {{-- <div class="absolute inset-0 bg-gray-300/30">
                </div> --}}

                {{-- <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-b from-gray-800 to-gray-500 text-white"> --}}
                <div class="absolute bottom-0 left-0 right-0 px-4 py-2 bg-gradient-to-b from-gray-100 to-gray-50">
                    <h2 class="text-xl font-semibold text-primary-800">{{ $tool->name }}</h2>
                    <p class="font-medium text-sm">{{ $tool->tag_line }}</p>
                </div>
            </div>
        @endif
    </a>


    <div class="flex flex-col justify-beetween gap-3 px-4 py-1">
        {{-- <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}"
            class="flex justify-center items-center text-xl font-semibold text-primary-700 hover:text-primary-800 two-lines text-ellipsis">
            <span>{{ $tool->name }}</span>
            <small class="font-medium text-sm">- {{ $tool->tag_line }}</small>
        </a> --}}

        <p class="text-gray-700 two-lines">
            {{ $tool->summary }}
        </p>

        <ul class="flex flex-wrap items-center justify-start text-sm gap-2">
            <li title="Pricing type"
                class="flex items-center cursor-pointer gap-0.5 bg-gray-100 text-black px-2 py-0.5 rounded-full">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13 3.5C13 2.94772 12.5523 2.5 12 2.5C11.4477 2.5 11 2.94772 11 3.5V4.0592C9.82995 4.19942 8.75336 4.58509 7.89614 5.1772C6.79552 5.93745 6 7.09027 6 8.5C6 9.77399 6.49167 10.9571 7.5778 11.7926C8.43438 12.4515 9.58764 12.8385 11 12.959V17.9219C10.2161 17.7963 9.54046 17.5279 9.03281 17.1772C8.32378 16.6874 8 16.0903 8 15.5C8 14.9477 7.55228 14.5 7 14.5C6.44772 14.5 6 14.9477 6 15.5C6 16.9097 6.79552 18.0626 7.89614 18.8228C8.75336 19.4149 9.82995 19.8006 11 19.9408V20.5C11 21.0523 11.4477 21.5 12 21.5C12.5523 21.5 13 21.0523 13 20.5V19.9435C14.1622 19.8101 15.2376 19.4425 16.0974 18.8585C17.2122 18.1013 18 16.9436 18 15.5C18 14.1934 17.5144 13.0022 16.4158 12.1712C15.557 11.5216 14.4039 11.1534 13 11.039V6.07813C13.7839 6.20366 14.4596 6.47214 14.9672 6.82279C15.6762 7.31255 16 7.90973 16 8.5C16 9.05228 16.4477 9.5 17 9.5C17.5523 9.5 18 9.05228 18 8.5C18 7.09027 17.2045 5.93745 16.1039 5.17721C15.2467 4.58508 14.1701 4.19941 13 4.0592V3.5ZM11 6.07814C10.2161 6.20367 9.54046 6.47215 9.03281 6.8228C8.32378 7.31255 8 7.90973 8 8.5C8 9.22601 8.25834 9.79286 8.79722 10.2074C9.24297 10.5503 9.94692 10.8384 11 10.9502V6.07814ZM13 13.047V17.9263C13.7911 17.8064 14.4682 17.5474 14.9737 17.204C15.6685 16.7321 16 16.1398 16 15.5C16 14.7232 15.7356 14.1644 15.2093 13.7663C14.7658 13.4309 14.0616 13.1537 13 13.047Z"
                        fill="currentColor" />
                </svg>
                <span>{{ $tool->pricing_type }}</span>
            </li>

            @if ($tool->monthly_subscription_starts_from)
                <li title="Support for API"
                    class="flex items-center cursor-pointer gap-0.5 bg-gray-100 text-black px-2 py-0.5 rounded-full">
                    From <strong>{{ $tool->monthly_subscription_starts_from }}$</strong>
                </li>
            @endif

            @if ($tool->has_api)
                <li title="Support for API"
                    class="flex items-center cursor-pointer gap-0.5 bg-gray-100 text-black px-2 py-0.5 rounded-full">
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
            @foreach ($tool->categories as $key => $category)
                @if ($key > 4)
                @break
            @endif
            <li class="flex items-center gap-2">
                <span>{{ $category->name }}{{ $loop->last ? '' : ',' }}</span>
            </li>
        @endforeach
    </ul>
</div>

</li>
