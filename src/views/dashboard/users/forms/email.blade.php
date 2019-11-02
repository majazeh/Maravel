<div class="form-group">
    <label for="email">{{ _t('Email') }}</label>
    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" placeholder="{{ _t('Email') }}" value="{{ isset($user->email) ? $user->email : ''}}">
</div>
