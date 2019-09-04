@section('body-menu')
    @include('layouts.menu')
@show

{{-- <button id="btn-menu" class="d-none d-sm-none btn-menu menu-open" type="button">
    <i class="fas fa-btn-menu"></i>
</button> --}}

<div class="d-flex flex-column body">
    @section('body-navigation')
        @include('layouts.navigation')
    @show

    <div class="d-flex body-content">
        @if (false)
            @section('body-desktop')
                @include('layouts.desktop')
            @show
        @endif

        @section('body-main')
            @include('layouts.main')
        @show
    </div>
</div>

@include('layouts.footer')
