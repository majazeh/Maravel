<div class="form-group">
    <label for="email">
        {{ _t('Email') }}
        <span data-xhr="uniq-email"></span>
    </label>
    <input class="form-control lijax" data-lijax="blur 500" data-xhrBase="uniqCheck" data-query="unique{!!isset($id) ? "&user=$id" : ''!!}" data-action="{{route('dashboard.users.index')}}" type="email" name="email" id="email" placeholder="{{ _t('Email') }}" value="{{ isset($user->email) ? $user->email : ''}}">
</div>
