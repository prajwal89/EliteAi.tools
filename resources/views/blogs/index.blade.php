@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)



@section('content')
    <div class="mx-auto max-w-3xl px-2 min-h-screen">

        <h1 class="font-bold text-2xl sm:text-3xl md:text-4xl mt-4">
            Blog Home
        </h1>

        <ul class="flex flex-col gap-4 font-semibold text-lg md:text-xl my-4">
            @foreach ($allBlogs as $blog)
                <li><a href="{{ route('blog.show', ['blog' => $blog->slug]) }}">{{ $blog->title }}</a></li>
            @endforeach
        </ul>
    </div>
@stop
