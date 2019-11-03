@extends($layouts->mode =='html' ? 'layouts.app' : 'templates.app-xhr')

@section('head-styles')
    @include('layouts.head-styles')
@endsection

@section('body')
    <body data-page="{{ Route::current()->getName() }}" class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading font-dana">
        @yield('main')
        @include('layouts.scripts')
    </body>
@endsection
