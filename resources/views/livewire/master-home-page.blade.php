<div>

    @include('partials.hero')

    <div class="px-2 md:px-8 mb-16">

        @if (isset($category) && $pageType == 'category')
            <ul class="flex flex-col gap-4 w-full max-w-5xl mx-auto">
                @foreach ($category->tools as $cTool)
                    <x-tool-cards.horizontal-card :tool="$cTool" />
                @endforeach
            </ul>
        @endif

        @if ($pageType == 'home')
            @if (isset($recentTools))
                <div class="flex justify-center relative">
                    <span class="text-2xl md:text-3xl font-bold">
                        Recently Added
                    </span>
                    {{-- <span
                        class="absolute -bottom-1 left-0 w-full h-1 bg-gradient-to-r from-primary-500 via-gray-400 to-primary-600 rounded-full"></span> --}}
                </div>

                <ul class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 p-2 xl:p-5 ">
                    @foreach ($recentTools as $vTool)
                        <x-tool-cards.square-card :tool="$vTool" />
                    @endforeach
                </ul>
            @endif
        @endif


        @if (isset($popularSearchesTools) && $pageType == 'popularSearches')
            <ul class="flex flex-col gap-4 w-full max-w-5xl mx-auto">
                @foreach ($popularSearchesTools as $cTool)
                    <x-tool-cards.horizontal-card :tool="$cTool" />
                @endforeach
            </ul>
        @endif


        @if ($pageType == 'search')
            @if (isset($searchResults['tools']))
                <div class="flex justify-center relative text-2xl md:text-3xl font-bold mb-4">
                    Search results
                </div>
                @if ($searchResults['tools']->count() > 0)
                    <ul class="flex flex-col gap-4 w-full max-w-5xl mx-auto">
                        @foreach ($searchResults['tools'] as $rTool)
                            <x-tool-cards.horizontal-card :tool="$rTool" />
                        @endforeach
                    </ul>
                @else
                    <div class="flex justify-center items-center">
                        <p>No results found</p>
                    </div>
                @endif
            @endif

            {{-- filter model --}}
            {{-- <dialog id="filtersModal" class="bg-white p-2 md:px-4 w-full max-w-xl rounded-md">
                <h3 class="mb-4 font-medium text-gray-900">Filters</h3>
                <div class="my-4">
                    <div class="mt-2">
                        <strong>Pricing Type</strong>
                        <select wire:model.defer="filters.pricingType"
                            class="w-full border bg-gray-100 rounded-md px-2 py-1 text-lg">
                            @foreach (\App\Enums\PricingType::cases() as $pricingType)
                                <option value="{{ $pricingType->value }}">{{ $pricingType->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4 flex gap-2 justify-end">
                        <button onclick="return document.getElementById('filtersModal').close()" type="button"
                            class="inline-flex justify-center rounded border border-gray-300 px-4 py-1 bg-white text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            close
                        </button>
                        <button wire:click="applyFilters()"
                            onclick="return document.getElementById('filtersModal').close()" type="button"
                            class="inline-flex justify-center rounded border border-transparent px-4 py-1 bg-primary-600 text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:shadow-outline-primary transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            apply
                        </button>
                    </div>
                </div>
            </dialog> --}}
        @endif

    </div>
</div>
