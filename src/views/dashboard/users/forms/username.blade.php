<div class="form-group">
    <label for="username">{{ _t('Username') }}</label>
    <input class="form-control" type="text" name="username" id="username" placeholder="{{ _t('Username') }}" value="{{ isset($user->username) ? $user->username : ''}}">
</div>
