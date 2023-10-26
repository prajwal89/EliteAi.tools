@extends('layouts.app-full-width')
@section('title', $pageDataDTO->title)
@section('description', $pageDataDTO->description)
@section('conical_url', $pageDataDTO->conicalUrl)

@section('content')
    <div class="mx-auto max-w-3xl px-2 py-8 md:py-16">


        <div class="flex justify-center relative">
            <span class="text-2xl md:text-3xl font-semibold">
                Write us email at
                <br>
                <strong><a href="mailto:00prajwal@gmail.com">00prajwal@gmail.com</a></strong>
                <br>
                with your tool details
            </span>
        </div>

        {{-- <div method="POST" class="border p-4 md:p-8 rounded-lg shadow">
            <div class="flex justify-center relative">
                <span class="text-2xl md:text-3xl font-bold">
                    {{ $pageDataDTO->title }}
                </span>
            </div>

            <div class="my-4 text-lg">
                <label for="">Website URL</label>
                <input type="text" name="url" class="h-10 w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div class="my-4 text-lg">
                <label for="">Your email</label>
                <input type="text" name="url" class="h-10 w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div class="flex justify-end py-4">
                <button type="submit" class="btn-primary">Submit</button>
            </div>

        </div> --}}
    </div>
@stop
