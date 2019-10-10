@section('body-tag')
    <body data-page="{{Route::current()->getName()}}" class="d-flex rtl{{isset($module->bodyClass) ? ' '. join(' ', $module->bodyClass) : ''}}">
        @section('body')
            @include('layouts.body')
        @show
    </body>
@show
