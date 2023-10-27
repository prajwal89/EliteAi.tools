<div class="tab-pane fade show active" id="meta-panel" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Meta</p>

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
            <textarea type="text" rows="10" class="form-control" name="description" id="description">{{ $toolDto->description ?? '' }}</textarea>
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
                    <option value="{{ $toolDto->pricingType->value }}" selected>
                        {{ $toolDto->pricingType->value }}
                    </option>
                @endif
                @foreach (App\Enums\PricingType::cases() as $pricing)
                    <option value="{{ $pricing->value }}">{{ $pricing->value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
