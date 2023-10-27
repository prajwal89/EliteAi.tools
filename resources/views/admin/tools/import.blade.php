@extends('layouts.admin')
@section('title', 'Import tool')
@section('head')
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            Import tool
        </div>

        <div class="card-body">
            <div class="d-relative mb-4">
                <h4>Step1: Copy prompt</h4>
                <button class="btn btn-success" id="copy-button">Copy</button>
                <textarea class="form-control" name="" id="prompt" cols="100" rows="4">{{ $promptForSystem }}</textarea>
            </div>

            <hr>

            <div class="mb-4">
                <h4>Step 2: Get content</h4>
                @livewire('get-webpage-data')
            </div>

        </div>

    </div>

@stop

@section('scripts')
    @livewireScripts()

    <script>
        // Find the textarea and copy button elements
        var textarea = document.getElementById("prompt");
        var copyButton = document.getElementById("copy-button");

        // Add a click event listener to the copy button
        copyButton.addEventListener("click", function() {
            // Select the text in the textarea
            textarea.select();

            try {
                // Copy the selected text to the clipboard
                document.execCommand("copy");
                copyButton.innerHTML = 'Copied';
                // alert("Text copied to clipboard");
            } catch (err) {
                console.error("Unable to copy text to clipboard");
            }
        });
    </script>

@stop
