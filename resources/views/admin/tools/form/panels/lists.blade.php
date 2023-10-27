<div class="tab-pane fade" id="lists" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Lists</p>

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
                        <input type="text" value="{{ $feature }}" class="form-control" name="top_features[]">
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
    </div>
</div>
