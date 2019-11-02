@if (Guardio::has('users.change.type'))
    <div class="form-group">
        <label for="type">{{ _t('user.type') }}</label>
        <select class="form-control kt-selectpicker" name="type" id="type">
            @foreach (config('guardio.type', ['admin', 'user']) as $type => $value)
                @isset ($user)
                    <option value="{{ $value }}" {{ $user->type == $value ? 'selected="selected"' : '' }}>{{ _t("type.$value") }}</option>
                @else
                    <option value="{{ $value }}" {{ $value == 'user' ? 'selected="selected"' : '' }}>{{ _t("type.$value") }}</option>
                @endisset
            @endforeach
        </select>
    </div>
@endif
