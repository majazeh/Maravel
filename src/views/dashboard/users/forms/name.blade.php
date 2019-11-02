<div class="form-group">
    <label for="name">
        {{ _t('Name') }} <small class="d-inline-block text-secondary">{{_t('optional')}}</small>
    </label>
    <input class="form-control" type="text" name="name" id="name" placeholder="{{ _t('Name') }}" value="{{ isset($user->name) ? $user->name : ''}}">
</div>
