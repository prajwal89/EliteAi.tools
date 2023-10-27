<div class="tab-pane fade" id="extensions" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Extensions</p>
        <div class="form-group mb-4">
            <label class="fw-bold">chrome_extension_id</label>
            <input type="text" placeholder="nlipoenfbbikpbjkfpfillcgkoblgpmj"
                value="{{ $tool->chrome_extension_id ?? '' }}" class="form-control" name="chrome_extension_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">firefox_extension_id</label>
            <input type="text" placeholder="enhancer-for-youtube" class="form-control"
                value="{{ $tool->firefox_extension_id ?? '' }}" name="firefox_extension_id">
        </div>
    </div>
</div>
