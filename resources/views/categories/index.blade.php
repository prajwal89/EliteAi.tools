@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)



@section('content')
    <div class="mx-auto max-w-3xl px-2">

        <h1 class="font-bold text-2xl sm:text-3xl md:text-4xl mt-4">
            All categories
        </h1>

        <ul class="flex flex-wrap justify-center py-4 mb-8 gap-4">
            @foreach ($categories as $category)
                <li class="border border-primary-600 px-4 py-2 rounded-md hover:bg-primary-100">
                    <a href="{{ route('category.show', ['category' => $category->slug]) }}">
                        <span class="text-lg">{{ $category->name }}</span>
                        <span
                            class="bg-primary-800 p-1 rounded-full text-white font-bold ml-2">{{ $category->tools_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

    </div>
@stop
