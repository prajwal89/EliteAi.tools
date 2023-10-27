<div class="tab-pane fade show active" id="meta-panel" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Meta</p>

        <div class="form-group mb-4">
            <label class="fw-bold">*name <small>(Should be short e.x. ChatGpt)</small></label>
            <input type="text" class="form-control" value="{{ $tool->name ?? ($toolDto->name ?? '') }}" name="name"
                required>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">*tag_line</label>
            <input type="text" class="form-control" value="{{ $tool->tag_line ?? ($toolDto->tagLine ?? '') }}"
                name="tag_line" required>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">*summary</label>
            <textarea type="text" class="form-control" name="summary" required>{{ $tool->summary ?? ($toolDto->summary ?? '') }}</textarea>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">*description</label>
            <textarea type="text" rows="10" class="form-control" name="description" id="description" required>{{ $tool->description ?? ($toolDto->description ?? '') }}</textarea>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">*categories</label>
            <select id="categories" name="categories[]" multiple placeholder="Select Appropriate Categories"
                autocomplete="off" class="rounded-lg" required>
                @foreach (\App\Models\Category::all() as $catgory)
                    <option value="{{ $catgory->id }}">{{ $catgory->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">*domain_name</label>
            <input type="text" class="form-control" name="domain_name" required
                value="{{ isset($tool->domain_name) ? $tool->domain_name : (isset($home_page_url) ? getDomainFromUrl($home_page_url) : '') }}">
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">*home_page_url</label>
            <input type="text" class="form-control" value="{{ $tool->home_page_url ?? ($home_page_url ?? '') }}"
                name="home_page_url" required>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">contact_email</label>
            <input type="email" class="form-control" value="{{ $tool->contact_email ?? '' }}" name="contact_email">
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">
                <span>*uploaded_screenshot</span>
                <br>
                @if (!empty($tool->uploaded_screenshot))
                    <span class="text-success">Already available</span>
                @else
                    <a href="https://pikwy.com/" target="_blank">Capture</a>
                @endif
            </label>
            <input type="file" class="form-control" name="uploaded_screenshot"
                {{ request()->routeIs('admin.tools.create') ?? 'required' }}>
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">
                <span>*uploaded_favicon</span>
                <br>
                @if (!empty($tool->uploaded_favicon))
                    <span class="text-success">Already available</span>
                @else
                    <a href="https://onlineminitools.com/website-favicon-downloader" target="_blank">Fetch</a>
                @endif
            </label>
            <input type="file" class="form-control" name="uploaded_favicon"
                {{ request()->routeIs('admin.tools.create') ?? 'required' }}>
        </div>


        <div class="form-group mb-2">
            <label class="fw-bold">*pricing type</label>
            <select type="select" class="form-control" name="pricing_type" required>
                @if (isset($toolDto->pricingType))
                    <option value="{{ $toolDto->pricingType->value }}" selected>
                        {{ $toolDto->pricingType->value }}
                    </option>
                @endif

                @if (isset($tool->pricing_type))
                    <option value="{{ $tool->pricing_type->value }}" selected>
                        {{ $tool->pricing_type->value }}
                    </option>
                @endif

                @foreach (App\Enums\PricingType::cases() as $pricing)
                    <option value="{{ $pricing->value }}">{{ $pricing->value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
