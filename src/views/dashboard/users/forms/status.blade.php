@if (Guardio::has('users.change.status'))
    <div class="form-group">
        <label>{{ _t('account.status') }}</label>
        @foreach (config('guardio.status', ['awaiting', 'active', 'disable']) as $type => $value)
        <div class="kt-radio-list">
            @isset ($user)
                <label class="kt-radio">
                    <input type="radio" value="{{ $value }}" id="{{ $value }}" {{ $user->status == $value ? 'checked="checked"' : '' }} name="status">{{ _t("status.$value") }}
                    <span></span>
                </label>
            @else
                <label class="kt-radio">
                    <input type="radio" value="{{ $value }}" id="{{ $value }}" {{ $value == 'waiting' ? 'checked="checked"' : '' }} name="status">{{ _t("status.$value") }}
                    <span></span>
                </label>
            @endisset
        </div>
        @endforeach
    </div>
@endif
