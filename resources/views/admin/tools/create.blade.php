@extends('layouts.admin')
@section('title', 'Add new tool')
@section('content')
    <div class="card">

        <div class="card-header">
            Add new tool
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.tools.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label class="fw-bold">name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">tag_line</label>
                    <input type="text" class="form-control" name="tag_line">
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">summary</label>
                    <textarea type="text" class="form-control" name="summary"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">domain_name</label>
                    <input type="text" class="form-control" name="domain_name">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">home_page_url</label>
                    <input type="text" class="form-control" name="home_page_url">
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">uploaded_screenshot</label>
                    <input type="file" class="form-control" name="uploaded_screenshot">
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">uploaded_favicon</label>
                    <input type="file" class="form-control" name="uploaded_favicon">
                </div>


                {{-- <div class="form-group mb-4" id="top-features-container">
                    <label class="fw-bold">Top Features</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="top_features[]">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success add-top-feature">Add</button>
                        </div>
                    </div>
                </div> --}}


                <div class="form-group mb-4" id="top-features-container">
                    <label class="fw-bold">Top Features</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="top_features[]">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success add-top-feature">Add</button>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4" id="use-cases-container">
                    <label class="fw-bold">Use Cases</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="use_cases[]">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success add-use-case">Add</button>
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary my-3">
                    Add
                </button>
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
                    <button type="button" class="btn btn-success move-up">Move Up</button>
                    <button type="button" class="btn btn-success move-down">Move Down</button>
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
                } else {
                    handleButtonClick(event, topFeaturesContainer);
                    handleButtonClick(event, useCasesContainer);
                }
            });
        });
    </script>

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const topFeaturesContainer = document.getElementById("top-features-container");
            const addTopFeatureButton = document.querySelector(".add-top-feature");

            addTopFeatureButton.addEventListener("click", function() {
                const newInput = document.createElement("div");
                newInput.classList.add("input-group", "mt-2");
                newInput.innerHTML = `
                <input type="text" class="form-control" name="top_features[]">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-top-feature">Remove</button>
                    <button type="button" class="btn btn-success move-up">Move Up</button>
                    <button type="button" class="btn btn-success move-down">Move Down</button>
                </div>
                `;
                topFeaturesContainer.appendChild(newInput);
            });

            topFeaturesContainer.addEventListener("click", function(event) {
                if (event.target.classList.contains("remove-top-feature")) {
                    const inputGroup = event.target.closest(".input-group");
                    if (inputGroup) {
                        inputGroup.remove();
                    }
                } else if (event.target.classList.contains("move-up")) {
                    const inputGroup = event.target.closest(".input-group");
                    if (inputGroup && inputGroup.previousElementSibling) {
                        topFeaturesContainer.insertBefore(inputGroup, inputGroup.previousElementSibling);
                    }
                } else if (event.target.classList.contains("move-down")) {
                    const inputGroup = event.target.closest(".input-group");
                    if (inputGroup && inputGroup.nextElementSibling) {
                        topFeaturesContainer.insertBefore(inputGroup.nextElementSibling, inputGroup);
                    }
                }
            });
        });
    </script> --}}
@stop
