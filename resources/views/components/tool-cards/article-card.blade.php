<li class="mb-4 pb-6" id="{{ $tool->slug }}">
    <h2 class="flex gap-2 md:gap-4 items-center my-8">
        @if (!empty($tool->uploaded_favicon))
            <img class="h-8 w-8 md:h-10 md:w-10 bg-white shadow rounded"
                src="{{ asset('/tools/' . $tool->slug . '/favicon.webp') }}" alt="{{ $tool->name }} favicon"
                loading="lazy">
        @endif
        <div>
            <a class="font-bold text-xl sm:text-2xl" href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
                {{ $tool->name }}
            </a>
            <br>
            <span class="font-semibold text-gray-600">{{ $tool->tag_line }}</span>
        </div>
    </h2>

    @if (!empty($tool->uploaded_screenshot))
        <img class="w-full mx-auto shadow-sm shadow-gray-200 rounded max-w-3xl"
            src="{{ asset('/tools/' . $tool->slug . '/screenshot.webp') }}" alt="{{ $tool->name }}">
    @endif

    <p class="text-lg my-4">
        {{ $tool->summary }}
    </p>

    <div class="text-lg flex gap-2 items-center py-2">
        <span class="font-semibold">Pricing:</span>
        <div title="Pricing type"
            class="flex items-center cursor-pointer gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $tool->pricing_type }}</span>
        </div>
        @if ($tool->monthly_subscription_starts_from)
            <div
                class="flex items-center cursor-pointer gap-0.5 bg-gray-100 shadow-sm text-black px-2 py-0.5 rounded-full">
                Starts from <strong class="text-xl">{{ $tool->monthly_subscription_starts_from }}$</strong>
            </div>
        @endif
    </div>

    <div class="text-lg py-2">
        <span class="font-semibold">Key Features:</span>
        <ul class="text-lg list-disc pl-6">
            @foreach ($tool->top_features as $feature)
                <li class="list-disc">
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    </div>

    <div class="text-lg py-2">
        <span class="font-semibold">Use Cases:</span>
        <ul class="text-lg list-disc pl-6">
            @foreach ($tool->use_cases as $useCase)
                <li class="list-disc">
                    {{ $useCase }}
                </li>
            @endforeach
        </ul>
    </div>

    <div class="flex justify-center gap-2 py-2">
        <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}" class="btn-outline-primary">
            View more details
        </a>
        <a href="{{ $tool->home_page_url }}" target="_blank" class="btn-outline-primary">
            Visit {{ $tool->name }}
        </a>
    </div>

</li>
