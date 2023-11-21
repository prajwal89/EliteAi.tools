@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('content')
    <div class="mx-auto max-w-3xl px-2">

        <h1 class="font-bold text-2xl sm:text-3xl md:text-4xl mt-4">
            {{ $blog->title }}
            @if (isAdmin())
                <a href="{{ route('admin.blogs.edit', $blog->id) }}">...</a>
            @endif
        </h1>

        <p class="mt-2 text-lg">
            {{ $blog->description }}
        </p>

        <div class="mt-6">
            <h3 class="font-bold mb-2 text-lg">Table of Contents</h3>
            <ul class="pl-2 space-y-2">
                @foreach ($tools as $tool)
                    <li class=""><a href="#{{ $tool->slug }}">- {{ $tool->name }}</a></li>
                @endforeach
            </ul>
        </div>


        <ul class="">
            @foreach ($tools as $tool)
                <x-tool-cards.article-card :tool="$tool" />
            @endforeach
        </ul>
    </div>
@stop
