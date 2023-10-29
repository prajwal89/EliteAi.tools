<div class="tab-pane fade" id="apps" role="tabpanel">
    <div class="border p-2 my-4">
        <p class="fw-bold text-success text-lg">Apps</p>
        <div class="form-group mb-4">
            <label class="fw-bold">android_app_id</label>
            <input type="text" placeholder="com.mcqmate.app"
                value="{{ $toolSocialHandlesDTO->androidApId ?? ($tool->android_app_id ?? '') }}" class="form-control"
                name="android_app_id">
        </div>
        <div class="form-group mb-4">
            <label class="fw-bold">ios_app_id</label>
            <input type="text" placeholder="id6445975220"
                value="{{ $toolSocialHandlesDTO->IOSAppID ?? ($tool->ios_app_id ?? '') }}" class="form-control"
                name="ios_app_id">
        </div>
    </div>
</div>
