<div class="kt-header__topbar">
    @section('header-topbar')
        @if (false)
            @include('layouts.header.header-search')
            @include('layouts.header.header-notifications')
            @include('layouts.header.header-actions')
            @include('layouts.header.header-cart')
            @include('layouts.header.header-panel-toggler')
            @include('layouts.header.header-language')
        @endif
        @include('layouts.header.header-user')
    @show
</div>