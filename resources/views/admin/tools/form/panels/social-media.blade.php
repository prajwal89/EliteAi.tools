<div class="tab-pane fade" id="social-media" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Social media</p>
        <div class="form-group mb-4">
            <label class="fw-bold">twitter_id</label>
            <input type="text" placeholder="prajwal_hallae"
                value="{{ $toolSocialHandlesDTO->twitterUserId ?? ($tool->twitter_id ?? '') }}" class="form-control"
                name="twitter_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">instagram_id</label>
            <input type="text" placeholder="prajwal_hallae"
                value="{{ $toolSocialHandlesDTO->instagramUserId ?? ($tool->instagram_id ?? '') }}" class="form-control"
                name="instagram_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">tiktok_id</label>
            <input type="text" placeholder="prajwal_hallae"
                value="{{ $toolSocialHandlesDTO->tiktokUserId ?? ($tool->tiktok_id ?? '') }}" class="form-control"
                name="tiktok_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">linkedin_id</label>
            <input type="text" placeholder="prajwal_hallae" value="{{ $tool->linkedin_id ?? '' }}"
                class="form-control" name="linkedin_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">linkedin_company_id</label>
            <input type="text" placeholder="prajwal_hallae"
                value="{{ $toolSocialHandlesDTO->linkedinCompanyId ?? ($tool->linkedin_company_id ?? '') }}"
                class="form-control" name="linkedin_company_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">facebook_id</label>
            <input type="text" placeholder="100084529884548" class="form-control" name="facebook_id"
                value="{{ $toolSocialHandlesDTO->facebookUserId ?? ($tool->facebook_id ?? '') }}" />
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">youtube_channel_id</label>
            <input type="text" placeholder="UCFO-zeRBQpoKecuOYyG4Mcw" class="form-control" name="youtube_channel_id"
                value="{{ $toolSocialHandlesDTO->youtubeChannelId ?? ($tool->youtube_channel_id ?? '') }}">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">discord_channel_invite_id</label>
            <input type="text" placeholder="" class="form-control" name="discord_channel_invite_id"
                value="{{ $toolSocialHandlesDTO->discordChannelInviteId ?? ($tool->discord_channel_invite_id ?? '') }}">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">telegram_channel_id</label>
            <input type="text" placeholder="" class="form-control" name="telegram_channel_id"
                value="{{ $toolSocialHandlesDTO->telegramChannelId ?? ($tool->telegram_channel_id ?? '') }}">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">subreddit_id</label>
            <input type="text" placeholder="" class="form-control" name="subreddit_id"
                value="{{ $toolSocialHandlesDTO->subredditId ?? ($tool->subreddit_id ?? '') }}">
        </div>
    </div>
</div>
