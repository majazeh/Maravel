<div class="form-group mb-0 form-inline">
    <label for="status" class="mr-3">
            {{ _t('Status') }}
        </label>
    <span class="kt-switch kt-switch--icon">
        <label>
            <input type="checkbox" name="status" id="status" {!! isset($resultName) && isset(${$resultName}->status) && ${$resultName}->status == 'available' ? 'checked' : ''!!}>
            <span></span>
        </label>
    </span>
</div>
