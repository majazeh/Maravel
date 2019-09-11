@if (\Auth::guardio('users.change.status'))
<div class="form-group">
    <label>{{ _t('account.status') }}</label>
    @foreach (config('guardio.status', ['awaiting', 'active', 'disable']) as $type => $value)
    <div class="custom-control custom-radio">
        @isset ($user)
        <input type="radio" value="{{ $value }}" id="{{ $value }}" {{ $user->status == $value ? 'checked="checked"' : '' }} name="status" class="custom-control-input">
        @else
        <input type="radio" value="{{ $value }}" id="{{ $value }}" {{ $value == 'waiting' ? 'checked="checked"' : '' }} name="status" class="custom-control-input">
        @endisset
        <label class="custom-control-label f2 text-secondary" for="{{ $value }}">{{ _t("user.status.$value") }}</label>
    </div>
    @endforeach
</div>
@endif
