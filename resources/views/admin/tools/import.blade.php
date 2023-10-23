@extends('layouts.admin')
@section('title', 'Import tool from json')
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@stop

@section('content')
    <div class="card">

        <div class="card-header">
            Import tool from json
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.tools.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label class="fw-bold">Website Home Page</label>
                    <input type="url" class="form-control" name="home_page_url" required />
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">tool_json_string</label>
                    <textarea type="text" class="form-control" rows="10" name="tool_json_string" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary float-right my-3">
                    Import
                </button>
            </form>
        </div>


        <div class="p-4">
            @php

                $prompt = \App\Services\ExtractedToolProcessor::buildSystemPrompt(public_path('/prompts/prompt.txt'));

                echo nl2br($prompt);
            @endphp
        </div>

    </div>
@stop
