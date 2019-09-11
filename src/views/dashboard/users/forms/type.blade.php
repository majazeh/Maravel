@if (\Auth::guardio('users.change.type'))
<div class="form-group">
    <label for="type">{{ _t('user.type') }}</label>
    <select class="custom-select form-control" name="type" id="type">
        @foreach (config('guardio.type', ['admin', 'user']) as $type => $value)
        @isset ($user)
        <option value="{{ $value }}" {{ $user->type == $value ? 'selected="selected"' : '' }}>{{ _t("user.type.$value") }}</option>
        @else
        <option value="{{ $value }}" {{ $value == 'user' ? 'selected="selected"' : '' }}>{{ _t("user.type.$value") }}</option>
        @endisset
        @endforeach
    </select>
</div>
@endif
