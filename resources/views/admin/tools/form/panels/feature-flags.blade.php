<div class="tab-pane fade" id="feature-flags" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Features</p>
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkbox1" name="has_api"
                        {{ isset($toolDto) ? ($toolDto->hasApi ? 'checked' : '') : '' }}
                        {{ isset($tool) ? ($tool->has_api ? 'checked' : '') : '' }}>
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
</div>
