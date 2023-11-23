@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('content')
    <div class="w-full mx-auto max-w-7xl px-2 min-h-screen">

        <h1 class="font-bold text-center my-6 text-2xl sm:text-3xl md:text-4xl">
            Blog Home ({{ $allBlogs->count() }})
        </h1>

        {{-- <ul class="flex flex-col gap-4 font-semibold text-lg md:text-xl my-4">
            @foreach ($allBlogs as $blog)
                <li><a href="{{ route('blog.show', ['blog' => $blog->slug]) }}">{{ $blog->title }}</a></li>
            @endforeach
        </ul> --}}

        <section class="container mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            @foreach ($allBlogs as $blog)
                <div class="flex flex-col border rounded-lg overflow-hidden">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="aspect-ratio-3/2">
                        <img src="{{ asset('/blogs/' . $blog->slug . '/featured-large.webp') }}" alt="Card img"
                            class="object-cover w-full h-full" />
                    </a>
                    <div class="flex flex-col justify-between p-4">
                        <a href="{{ route('blog.show', $blog->slug) }}"
                            class="block mb-2 text-xl font-semibold leading-tight hover:underline hover:text-primary-600">
                            {{ $blog->title }}
                        </a>
                        <p class="mb-4 text-gray-600 two-lines">
                            {{ $blog->description }}
                        </p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-primary-600 hover:underline">Read
                            More
                        </a>
                    </div>
                </div>
            @endforeach
        </section>

    </div>
@stop
