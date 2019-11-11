<div class="form-group">
    <label for="mobile">
        {{ _t('Mobile') }}
        <span data-xhr="uniq-mobile"></span>
    </label>
    <input class="form-control lijax" data-lijax="blur 500" data-xhrBase="uniqCheck" data-query="unique{!!isset($id) ? "&user=$id" : ''!!}" data-action="{{route('dashboard.users.index')}}" type="tel" name="mobile" id="mobile" placeholder="{{ _t('Mobile') }}" value="{{ isset($user->mobile) ? $user->mobile : ''}}">
</div>
