@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('content')
    <div class="mx-auto max-w-3xl px-2 min-h-screen">

        <h1 class="font-bold text-2xl sm:text-3xl md:text-4xl mt-4">
            Tool Alternatives
        </h1>

        <ul class="flex flex-col gap-2 font-semibold text-lg md:text-xl my-4">
            @foreach ($allTools as $tool)
                <li>
                    <a href="{{ route('tool.alternatives.show', ['tool' => $tool->slug]) }}">
                        {{ $tool->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@stop
