<div class="tab-pane fade" id="others" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Other</p>

        <div class="form-group mb-4">
            <label class="fw-bold">yt_introduction_video_id</label>
            <input type="text" value="{{ $tool->yt_introduction_video_id ?? '' }}" placeholder="2mSg-6UJdWQ"
                class="form-control" name="yt_introduction_video_id">
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">vimeo_introduction_video_id</label>
            <input type="text" value="{{ $tool->vimeo_introduction_video_id ?? '' }}" placeholder="347119375"
                class="form-control" name="vimeo_introduction_video_id">
        </div>

        <div class="form-group mb-4">
            <label class="fw-bold">getParagraphForVectorEmbeddings (for show only)</label>
            <textarea class="form-control" name="" id="" rows="12">{{ isset($tool) ? $tool->getParagraphForVectorEmbeddings() : '' }}</textarea>
        </div>

    </div>
</div>
