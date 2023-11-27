<div class="px-4 md:px-8 pt-8 mx-auto bg-white border-t">
    <div class="grid gap-12 row-gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2">
            <a href="/" aria-label="Go home" title="{{ config('app.name') }}" class="inline-flex items-center">
                <img class="w-10" src="{{ asset('/images/logos/favicons/EliteAi.png') }}"
                    alt="{{ config('app.name') }} logo">
                <p class="ml-2  text-md md:text-xl font-bold text-black ">
                    Elite <span class="text-primary-600">AI</span> Tools
                </p>
            </a>
            <div class="mt-6 lg:max-w-sm">
                Find the exact AI tool for your requirements by searching with our AI-based search engine.
            </div>
        </div>
        <div class="space-y-2 text-sm">
            <p class="text-base font-bold tracking-wide text-gray-900">Contacts</p>
            {{-- <div class="flex">
                <p class="mr-1 text-gray-800 ">Phone:</p>
                <a href="tel:850-123-5021" aria-label="Our phone" title="Our phone">
                    850-123-5021
                </a>
            </div> --}}
            <div class="flex">
                <p class="mr-1 text-gray-800">Email:</p>
                <a href="mailto:{{ config('custom.admin_email') }}" aria-label="Our email" title="Our email"
                    class="">{{ config('custom.admin_email') }}
                </a>
            </div>
            {{-- <div class="flex">
                <p class="mr-1 text-gray-800">Address:</p>
                <p target="_blank" rel="noopener noreferrer" aria-label="Our address" title="Our address">
                    Xyx road Abc state
                </p>
            </div> --}}
        </div>
        <div class="text-gray-500">
            <p class="text-base font-bold tracking-wide text-gray-900">Links</p>
            <div class="flex gap-3 flex-col mt-2 text-sm">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('blog.index') }}">Blog</a>
                {{-- <a href="{{ route('search.popular.index') }}">Popular searches</a> --}}
            </div>
        </div>
    </div>
    <div class="text-sm flex flex-col-reverse justify-between py-2 border-t lg:flex-row">
        <p class="text-gray-600 ">
            © {{ date('Y') }}
            <a href="{{ route('home') }}" class="hover:underline">{{ config('app.name') }}</a>.
            All Rights Reserved.
        </p>
        <ul class="flex flex-col mb-3 space-y-2 lg:mb-0 sm:space-y-0 sm:space-x-5 sm:flex-row">
            <li>
                <a href="{{ route('privacy-policy') }}">
                    Privacy Policy
                </a>
            </li>
            <li>
                <a href="{{ route('terms-and-conditions') }}">
                    Terms &amp; Conditions
                </a>
            </li>
        </ul>
    </div>
</div>
