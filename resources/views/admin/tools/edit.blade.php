@extends('layouts.admin')
@section('title', 'Edit tool')
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@stop

@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <span>Edit tool</span>

            <div>
                <a class="btn btn-outline-primary float-right" href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
                    View
                </a>
                <a class="btn btn-outline-info float-right" href="{{ $tool->home_page_url }}">
                    Visit website
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    Delete
                </button>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.tools.update', ['tool' => $tool->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf

                @include('admin.tools.form.tab-buttons')

                <div class="tab-content" id="myTabContent">
                    @include('admin.tools.form.panels.meta-panal')
                    @include('admin.tools.form.panels.lists')
                    @include('admin.tools.form.panels.feature-flags')
                    @include('admin.tools.form.panels.social-media')
                    @include('admin.tools.form.panels.apps')
                    @include('admin.tools.form.panels.extensions')
                    @include('admin.tools.form.panels.others')
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary my-3">
                        Update Tool
                    </button>
                </div>

            </form>
        </div>

    </div>



    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete User: {{ $tool->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger"><strong>This action cannot be reversed</strong></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('admin.tools.destroy', ['tool' => $tool->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
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
        var tom_select = new TomSelect('#categories', {
            maxItems: 6,
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
