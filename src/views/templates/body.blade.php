@extends($layouts->mode =='html' ? 'layouts.app' : 'templates.body-xhr')

@section('body')
    <body data-page="{{ Route::current()->getName() }}" class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading @yield('font')">
        @yield('main')
        @include('layouts.scripts')
    </body>
@endsection
@include('dashboard.override')
