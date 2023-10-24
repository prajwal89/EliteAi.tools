<div>
    <div class="form-group mb-4">
        <label class="fw-bold">Website Home Page</label>
        <input type="url" class="form-control" wire:model="url" />
        <button class="btn btn-primary" wire:click='getData()'>GetData</button>
    </div>
    @if (!empty($contentForPrompt))
        <textarea class="form-control" name="" id="prompt" cols="100" rows="20">{{ $contentForPrompt }}</textarea>
    @endif
</div>
