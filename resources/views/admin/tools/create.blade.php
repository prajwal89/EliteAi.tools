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

                <div class="form-group mb-4">
                    <label class="fw-bold">name</label>
                    <input type="text" class="form-control" value="{{ $toolDto->name ?? '' }}" name="name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">tag_line</label>
                    <input type="text" class="form-control" value="{{ $toolDto->tagLine ?? '' }}" name="tag_line">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">summary</label>
                    <textarea type="text" class="form-control" name="summary">{{ $toolDto->summary ?? '' }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">categories</label>
                    <select id="categories" name="categories[]" multiple placeholder="Select Appropriate Categories"
                        autocomplete="off" class="rounded-lg">
                        @foreach (\App\Models\Category::all() as $catgory)
                            <option value="{{ $catgory->id }}">{{ $catgory->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group mb-4">
                    <label class="fw-bold">domain_name</label>
                    <input type="text" class="form-control" name="domain_name"
                        value="{{ getDomainFromUrl($home_page_url) }}">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">home_page_url</label>
                    <input type="text" class="form-control" value="{{ $home_page_url ?? '' }}" name="home_page_url">
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">uploaded_screenshot</label>
                    <input type="file" class="form-control" name="uploaded_screenshot">
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">uploaded_favicon</label>
                    <input type="file" class="form-control" name="uploaded_favicon">
                </div>


                <div class="form-group mb-2">
                    <label>pricing type</label>
                    <select type="select" class="form-control" name="pricing_type" required>
                        @if (isset($toolDto->pricingType))
                            <option value="{{ $toolDto->pricingType->value }}" selected>{{ $toolDto->pricingType->value }}
                            </option>
                        @endif
                        @foreach (App\Enums\PricingType::cases() as $pricing)
                            <option value="{{ $pricing->value }}">{{ $pricing->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-4" id="top-features-container">
                    <label class="fw-bold">Top Features</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="top_features[]">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success add-top-feature">Add</button>
                        </div>
                    </div>

                    @if (!empty($toolDto->topFeatures))
                        @foreach ($toolDto->topFeatures as $feature)
                            <div class="input-group mt-2">
                                <input type="text" value="{{ $feature }}" class="form-control"
                                    name="top_features[]">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-top_features">Remove</button>
                                    <button type="button" class="btn btn-success move-up">Up</button>
                                    <button type="button" class="btn btn-success move-down">Down</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="form-group mb-4" id="use-cases-container">
                    <label class="fw-bold">Use Cases</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="use_cases[]">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success add-use-case">Add</button>
                        </div>
                    </div>

                    @if (!empty($toolDto->useCases))
                        @foreach ($toolDto->useCases as $useCase)
                            <div class="input-group mt-2">
                                <input type="text" value="{{ $useCase }}" class="form-control" name="use_cases[]">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-use_cases">Remove</button>
                                    <button type="button" class="btn btn-success move-up">Up</button>
                                    <button type="button" class="btn btn-success move-down">Down</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
@stop
