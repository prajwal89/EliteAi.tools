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
                    <label class="fw-bold">*name <small>(Should be short e.x. ChatGpt)</small></label>
                    <input type="text" class="form-control" value="{{ $toolDto->name ?? '' }}" name="name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">tag_line</label>
                    <input type="text" class="form-control" value="{{ $toolDto->tagLine ?? '' }}" name="tag_line">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">*summary</label>
                    <textarea type="text" class="form-control" name="summary">{{ $toolDto->summary ?? '' }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">*description</label>
                    <textarea type="text" rows="10" class="form-control" name="description">{{ $toolDto->description ?? '' }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">*categories</label>
                    <select id="categories" name="categories[]" multiple placeholder="Select Appropriate Categories"
                        autocomplete="off" class="rounded-lg">
                        @foreach (\App\Models\Category::all() as $catgory)
                            <option value="{{ $catgory->id }}">{{ $catgory->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="form-group mb-4">
                    <label class="fw-bold">*domain_name</label>
                    <input type="text" class="form-control" name="domain_name"
                        value="{{ isset($home_page_url) ? getDomainFromUrl($home_page_url) : '' }}">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">*home_page_url</label>
                    <input type="text" class="form-control" value="{{ $home_page_url ?? '' }}" name="home_page_url">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">contact_email</label>
                    <input type="email" class="form-control" value="" name="contact_email">
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold">
                        uploaded_screenshot
                        <a href="https://pikwy.com/" target="_blank">Capture</a>
                    </label>
                    <input type="file" class="form-control" name="uploaded_screenshot" required>
                </div>


                <div class="form-group mb-4">
                    <label class="fw-bold">
                        uploaded_favicon
                        <a href="https://onlineminitools.com/website-favicon-downloader" target="_blank">Fetch</a>
                    </label>
                    <input type="file" class="form-control" name="uploaded_favicon" required>
                </div>


                <div class="form-group mb-2">
                    <label class="fw-bold">pricing type</label>
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
                                <input type="text" value="{{ $useCase }}" class="form-control"
                                    name="use_cases[]">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-use_cases">Remove</button>
                                    <button type="button" class="btn btn-success move-up">Up</button>
                                    <button type="button" class="btn btn-success move-down">Down</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>


                <div class="border p-2 my-4">
                    <p class="fw-bold text-success text-lg">Features</p>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox1" name="has_api"
                                    {{ isset($toolDto) ? ($toolDto->hasApi ? 'checked' : '') : '' }}>
                                <label class="form-check-label" for="checkbox1">Has API</label>
                            </div>
                        </div>

                        {{-- <div class="col-12 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox2"
                                    {{ isset($toolDto) ? ($toolDto->hasDocumentation ? 'selected' : '') : '' }}>
                                <label class="form-check-label" for="checkbox2">Has Documentation</label>
                            </div>
                        </div>

                    <div class="col-12 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox3">
                                <label class="form-check-label" for="checkbox3">Checkbox 3</label>
                            </div>
                        </div> --}}
                    </div>
                </div>



                <div class="border p-2 my-4">
                    <p class="fw-bold text-success text-lg">Social media</p>
                    <div class="form-group mb-4">
                        <label class="fw-bold">twitter_id</label>
                        <input type="text" placeholder="prajwal_hallae" class="form-control" name="twitter_id">
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">instagram_id</label>
                        <input type="text" placeholder="prajwal_hallae" class="form-control" name="instagram_id">
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">tiktok_id</label>
                        <input type="text" placeholder="prajwal_hallae" class="form-control" name="tiktok_id">
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">linkedin_id</label>
                        <input type="text" placeholder="prajwal_hallae" class="form-control" name="linkedin_id">
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">linkedin_company_id</label>
                        <input type="text" placeholder="prajwal_hallae" class="form-control"
                            name="linkedin_company_id">
                    </div>
                </div>

                <div class="border p-2 my-4">
                    <p class="fw-bold text-success text-lg">Apps</p>
                    <div class="form-group mb-4">
                        <label class="fw-bold">android_app_id</label>
                        <input type="text" placeholder="com.mcqmate.app" class="form-control" name="android_app_id">
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">ios_app_id</label>
                        <input type="text" placeholder="id6445975220" class="form-control" name="ios_app_id">
                    </div>
                </div>

                <div class="border p-2 my-4">
                    <p class="fw-bold text-success text-lg">Extensions</p>
                    <div class="form-group mb-4">
                        <label class="fw-bold">chrome_extension_id</label>
                        <input type="text" placeholder="nlipoenfbbikpbjkfpfillcgkoblgpmj" class="form-control"
                            name="chrome_extension_id">
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">firefox_extension_id</label>
                        <input type="text" placeholder="enhancer-for-youtube" class="form-control"
                            name="firefox_extension_id">
                    </div>
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
        var tom_select = new TomSelect('#categories', {
            maxItems: 6,
            plugins: ['remove_button'],
            items: @json($categoryIds ?? [])
        });
    </script>
@stop
