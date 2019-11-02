<div class="form-group">
    <label for="password">{{ _t('password') }}</label>
    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" placeholder="{{ _t('password') }}" autocomplete="new-password">
</div>
