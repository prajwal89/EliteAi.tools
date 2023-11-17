<div class="px-4 py-6 bg-gradient-to-b from-primary-50 via-primary-50 to-white relative">
    <div class="flex flex-col items-center w-full min-h-[280px] md:min-h-[420px] relative">
        <div class="absolute inset-0 blur-[4px]">
            <img class="animate-img opacity-90 absolute w-10 h-10" style="top: 70%; left: 46%;"
                src="{{ asset('/images/home/1.svg') }}">
            <img class="animate-img hidden md:block opacity-90 absolute w-10 h-10" style="top: 55%; left: 13%;"
                src="{{ asset('/images/home/2.svg') }}">
            <img class="animate-img opacity-90 absolute w-10 h-10" style="top: 25%; left: 14%;"
                src="{{ asset('/images/home/3.svg') }}">
            <img class="animate-img hidden md:block opacity-90 absolute w-10 h-10" style="top: 28%; right: 12%"
                src="{{ asset('/images/home/4.svg') }}">
            <img class="animate-img opacity-90 absolute w-10 h-10" style="top: 5%; right: 25%"
                src="{{ asset('/images/home/5.svg') }}">
            <img class="animate-img opacity-90 absolute w-10 h-10" style="top: 80%; right: 5%"
                src="{{ asset('/images/home/6.svg') }}">
            <img class="animate-img hidden md:block opacity-90 absolute w-10 h-10" style="top: 95%; right: 32%"
                src="{{ asset('/images/home/7.svg') }}">
            <img class="animate-img opacity-90 absolute w-10 h-10" style="left: 4%; bottom: 1%"
                src="{{ asset('/images/home/8.svg') }}">
            <img class="animate-img hidden md:block opacity-90 absolute w-10 h-10" style="top: 5%; right: 50%"
                src="{{ asset('/images/home/9.svg') }}">
            <img class="animate-img hidden md:block opacity-90 absolute w-10 h-10" style="top: 5%; right: 1%"
                src="{{ asset('/images/home/10.svg') }}">
            <img class="animate-img hidden md:block opacity-90 absolute w-10 h-10" style="top: 4%; left: 4%"
                src="{{ asset('/images/home/11.svg') }}">
        </div>

        <h1 class="mx-auto py-2 md:py-6 text-center text-shadow-xl z-20">
            <span class="block font-bold text-2xl sm:text-3xl md:text-5xl">
                @if (isset($category))
                    {{ $category->tools()->count() }} {{ $category->name }} AI tools

                    @if (isAdmin())
                        <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}">...</a>
                    @endif
                @elseif (isset($searchRelatedTools))
                    {{ $pageDataDTO->title }}

                    @if (isAdmin())
                        <a href="{{ route('admin.top-searches.edit', ['top_search' => $topSearch->id]) }}">...</a>
                    @endif
                @else
                    {{ config('app.name') }}
                @endif
            </span>
            <span class="text-lg block sm:text-xl pt-4 w-full max-w-5xl mx-auto">
                @if (isset($category))
                    {!! nl2br($category->description) !!}
                @elseif (isset($searchRelatedTools))
                @else
                    Find the one you need
                @endif
            </span>
        </h1>


        <form action="{{ route('search.show') }}" class="relative w-full max-w-3xl my-4 md:my-8">
            <input class="w-full border h-16 shadow p-4 pr-12 rounded-full text-lg" name="query"
                value="{{ $query ?? '' }}" placeholder="I want to chat with PDF">
            <button type="submit" class="absolute top-4 right-4 w-8 h-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
        </form>



        @if (!empty($allCategories))
            <ul class="flex gap-2 md:gap-3 flex-wrap justify-center max-w-5xl py-2 md:py-6 px-2 md:px-8">
                @foreach ($allCategories as $c)
                    @if (isset($category) && $c->name == $category->name)
                        <li
                            class="text-center border border-primary-500 px-2 py-1 md:text-lg relative md:min-w-[100px] flex justify-center items-center bg-gray-100 rounded-lg select-none cursor-pointer shadow shadow-primary-500 outline outline-primary-600">
                            <a class="w-full h-full" href="{{ route('category.show', ['category' => $c->slug]) }}">
                                {{ $c->name }}
                            </a>
                        </li>
                    @else
                        <li
                            class="text-center border border-primary-500 px-2 py-1 md:text-lg relative md:min-w-[100px] flex justify-center items-center bg-gray-100 rounded-lg select-none cursor-pointer hover:shadow hover:shadow-primary-500 hover:outline hover:outline-primary-600">
                            <a class="w-full h-full" href="{{ route('category.show', ['category' => $c->slug]) }}">
                                {{ $c->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
                <li
                    class="text-center border border-primary-500 px-2 py-1 md:text-lg relative md:min-w-[100px] flex justify-center items-center bg-gray-100 rounded-lg select-none cursor-pointer hover:shadow hover:shadow-primary-500 hover:outline hover:outline-primary-600">
                    <a class="w-full h-full" href="{{ route('category.index') }}">
                        More +
                    </a>
                </li>
            </ul>
        @endif

    </div>
</div>

<style>
    .animate-img {
        animation: bounce 3s infinite alternate;
    }

    @keyframes bounce {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-12px);
        }
    }
</style>
