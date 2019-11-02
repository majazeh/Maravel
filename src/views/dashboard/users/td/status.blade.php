@php
$statusStyle = 'secondary';
    switch ($user->status) {
    case 'active':
        $statusStyle = 'success';
        break;
    case 'waiting':
        $statusStyle = 'warning';
            break;
    case 'block':
        $statusStyle = 'danger';
            break;
    }
@endphp
<span class="badge badge-{{$statusStyle}} badge-pill">
    {{ _t("status.$user->status") }}
</span>
