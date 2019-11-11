<div class="form-group">
    <label for="username">
        {{ _t('Username') }}
        <span data-xhr="uniq-username"></span>
    </label>
    <input class="form-control lijax" data-lijax="blur 500" data-xhrBase="uniqCheck" data-query="unique{!!isset($id) ? "&user=$id" : ''!!}" data-action="{{route('dashboard.users.index')}}" type="text" name="username" id="username"  placeholder="{{ _t('Username') }}" value="{{ isset($user->username) ? $user->username : ''}}">
</div>
