@if (in_array($user->type, config('guardio.admins', ['admin'])))
    <span class="fas fa-user-tie text-success"></span>
    <span class="font-weight-bold text-success">{{ _t("type.$user->type") }}</span>
@else
    {{ _t("type.$user->type") }}
@endif
