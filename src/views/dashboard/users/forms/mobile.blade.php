<div class="form-group">
    <label for="mobile">{{ _t('Mobile') }}</label>
    <input class="form-control" type="tel" name="mobile" id="mobile" placeholder="{{ _t('Mobile') }}" value="{{ isset($user->mobile) ? $user->mobile : ''}}">
</div>
