@if (Guardio::has('users.change.groups'))
    <div class="form-group mb-0">
        <label for="groups">{{ _t('user.groups') }}</label>
        <select class="form-control kt-selectpicker" name="groups[]" id="groups[]" multiple>
            @foreach ($groups ?? [] as $key => $value)
                @isset ($user)
                    <option value="{{ $value }}" {{ in_array($value, $user->groups) ? 'selected="selected"' : '' }}>{{ _t("groups.$value") }}</option>
                @else
                    <option value="{{ $value }}" {{ $value == 'user' ? 'selected="selected"' : '' }}>{{ _t("groups.$value") }}</option>
                @endisset
            @endforeach
        </select>
    </div>
@endif
