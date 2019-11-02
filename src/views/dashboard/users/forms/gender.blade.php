<div class="form-group">
    <label>
        {{ _t('gender') }} <small class="d-inline-block text-secondary">{{_t('optional')}}</small>
    </label>
    <div class="kt-radio-list">
        <label class="kt-radio">
            <input type="radio" value="female" id="female" name="gender" {{ isset($user->gender) && $user->gender == 'female' ? 'checked="checked"' : '' }}>{{ _t('female') }}
            <span></span>
        </label>
        <label class="kt-radio">
            <input type="radio" value="male" id="male" name="gender" {{ isset($user->gender) && $user->gender == 'male' ? 'checked="checked"' : '' }}>{{ _t('male') }}
            <span></span>
        </label>
    </div>
</div>
