@php
    $xhr_type = '';
    $result = false;
    if(request()->username)
    {
        if(strlen(request()->username) >= 3)
            $result = true;
        $xhr_type = 'username';
    }
    elseif(request()->email)
    {
        if(strlen(request()->email) >= 3)
            $result = true;
        $xhr_type = 'email';
    }
    elseif(request()->mobile)
    {
        list($mobile, $country, $code) = \Maravel\Lib\MobileRV::parse(request()->mobile);
        if($mobile)
            $result = true;
        $xhr_type = 'mobile';
    }
@endphp
<span data-xhr="uniq-{{$xhr_type}}">
    @if ($result)
        @if (count($users))
            <span class="kt-badge kt-badge--danger kt-badge--dot"></span>
            <span class="kt-font-danger">{{_t('Reserved')}}</span>
        @else
            <span class="kt-badge kt-badge--success kt-badge--dot"></span>
            <span class="kt-font-success">{{_t('Open')}}</span>
        @endif
    @endif
</span>
