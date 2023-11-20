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
            <div class="fixed z-10 inset-0 overflow-y-auto hidden">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                    <div
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="mb-4 leading-6 font-medium text-gray-900">
                                Filters
                            </h3>

                            <div class="my-4">

                                <div class="mt-2">
                                    <strong>Pricing Type</strong>
                                    <select wire:model="pricingType"
                                        class="w-full border bg-gray-100 rounded-md px-2 py-1 text-lg">
                                        @foreach (\App\Enums\PricingType::cases() as $pricingType)
                                            <option value="{{ $pricingType->name }}">{{ $pricingType->value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <button type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-primary-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:shadow-outline-primary transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    apply
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    close
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
