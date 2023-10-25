@extends('layouts.app-full-width')

@section('content')

    @include('partials.hero')

    <div class="px-2 md:px-8 mb-16">

        {{-- category tools --}}
        @if (isset($category))
            <ul class="flex flex-col gap-4 w-full max-w-5xl mx-auto">
                @foreach ($category->tools as $cTool)
                    <x-tool-card.horizontal :tool="$cTool" />
                @endforeach
            </ul>
        @endif

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
                    <x-tool-card.square :tool="$vTool" />
                @endforeach
            </ul>
        @endif

    </div>
@stop
