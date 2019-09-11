<div class="form-group">
    <label>
        {{ _t('gender') }} <small class="text-secondary">{{_t('optional')}}</small>
    </label>
    <div class="custom-control custom-radio">
        <input type="radio" value="female" id="female" name="gender" class="custom-control-input" {{ isset($user->gender) && $user->gender == 'female' ? 'checked="checked"' : '' }}>
        <label class="custom-control-label f2 text-secondary" for="female">{{ _t('female') }}</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" value="male" id="male" name="gender" class="custom-control-input" {{ isset($user->gender) && $user->gender == 'male' ? 'checked="checked"' : '' }}>
        <label class="custom-control-label f2 text-secondary" for="male">{{ _t('male') }}</label>
    </div>
</div>
