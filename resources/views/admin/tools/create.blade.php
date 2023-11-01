@extends('layouts.admin')
@section('title', 'Add new tool')
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@stop

@section('content')
    <div class="card">

        <div class="card-header">
            Add new tool
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.tools.store') }}" enctype="multipart/form-data">
                @csrf

                @include('admin.tools.form.tab-buttons')

                <div class="tab-content" id="myTabContent">
                    @include('admin.tools.form.panels.meta-panal')
                    @include('admin.tools.form.panels.lists')
                    @include('admin.tools.form.panels.social-media')
                    @include('admin.tools.form.panels.feature-flags')
                    @include('admin.tools.form.panels.apps')
                    @include('admin.tools.form.panels.extensions')
                    @include('admin.tools.form.panels.others')
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success my-3 float-right  ">
                        Add The Tool
                    </button>
                </div>
            </form>
        </div>

    </div>
@stop


@section('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function addInput(container, inputName) {
                const newInput = document.createElement("div");
                newInput.classList.add("input-group", "mt-2");
                newInput.innerHTML = `
            <input type="text" class="form-control" name="${inputName}[]">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger remove-${inputName}">Remove</button>
                <button type="button" class="btn btn-success move-up">Up</button>
                <button type="button" class="btn btn-success move-down">Down</button>
            </div>
            `;
                container.appendChild(newInput);
            }

            function handleButtonClick(event, container) {
                if (event.target.classList.contains(`remove-${container.id}`)) {
                    const inputGroup = event.target.closest(".input-group");
                    if (inputGroup) {
                        inputGroup.remove();
                    }
                } else if (event.target.classList.contains("move-up")) {
                    const inputGroup = event.target.closest(".input-group");
                    if (inputGroup && inputGroup.previousElementSibling) {
                        container.insertBefore(inputGroup, inputGroup.previousElementSibling);
                    }
                } else if (event.target.classList.contains("move-down")) {
                    const inputGroup = event.target.closest(".input-group");
                    if (inputGroup && inputGroup.nextElementSibling) {
                        container.insertBefore(inputGroup.nextElementSibling, inputGroup);
                    }
                }
            }

            const topFeaturesContainer = document.getElementById("top-features-container");
            const useCasesContainer = document.getElementById("use-cases-container");

            document.addEventListener("click", function(event) {
                if (event.target.classList.contains("add-top-feature")) {
                    addInput(topFeaturesContainer, "top_features");
                } else if (event.target.classList.contains("add-use-case")) {
                    addInput(useCasesContainer, "use_cases");
                } else if (event.target.classList.contains("btn-danger")) {
                    // Added an extra condition for "Remove" buttons
                    handleButtonClick(event, topFeaturesContainer);
                    handleButtonClick(event, useCasesContainer);
                }
            });
        });
    </script>



    <script>
        new TomSelect('#categories', {
            maxItems: 10,
            plugins: ['remove_button'],
            items: @json($categoryIds ?? [])
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#description').summernote();
        });
    </script>
@stop
