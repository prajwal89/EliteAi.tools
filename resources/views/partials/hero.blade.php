<div class="px-4 mb-4 pb-8 bg-gradient-to-b from-primary-100 via-primary-50 to-white relative">
    <div class="flex flex-col items-center w-full min-h-[280px] md:min-h-[420px]  relative z-10">
        <div class="absolute inset-0 blur-[1px] -z-10">
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

        <h1 class="mx-auto my-8 md:my-12 text-center z-10">
            <span class="block font-bold text-2xl sm:text-3xl md:text-5xl">
                @if (isset($category))
                    <big>{{ $category->tools()->count() }}</big> {{ $category->name }} AI tools

                    @if (isAdmin())
                        <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}">...</a>
                    @endif
                @else
                    {{ config('app.name') }}
                @endif
            </span>
            <span class="text-lg block sm:text-xl pt-4 w-full max-w-5xl mx-auto">
                @if (isset($category))
                    {{ $category->description }}
                @else
                    Find the one you need
                @endif
            </span>
        </h1>

        <div class="relative w-full max-w-3xl">
            <input class="w-full border h-16 shadow p-4 rounded-full" name="">
            <svg class="absolute top-4 right-4 w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </div>


        <ul class="flex gap-3 my-8 md:my-12 flex-wrap justify-center px-4 md:px-8">
            @foreach ($categories as $c)
                @if (isset($category) && $c->name == $category->name)
                    <li
                        class="px-2 py-1 md:text-lg relative min-w-[100px] flex justify-center items-center bg-gray-100 rounded-lg select-none cursor-pointer shadow shadow-primary-500 outline outline-primary-600">
                        <a href="{{ route('category.show', ['category' => $c->slug]) }}">
                            {{ $c->name }}
                        </a>
                    </li>
                @else
                    <li
                        class="px-2 py-1 md:text-lg relative min-w-[100px] flex justify-center items-center bg-gray-100 rounded-lg select-none cursor-pointer hover:shadow hover:shadow-primary-500 hover:outline hover:outline-primary-600">
                        <a href="{{ route('category.show', ['category' => $c->slug]) }}">
                            {{ $c->name }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

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
