@extends($layouts->mode =='html' ? 'layouts.app' : 'templates.app-xhr')

@section('main')
    <form method="POST" {!! isset($multipart) ? 'enctype="multipart/form-data"' : '' !!} action="{{ $module->post_action }}">
        @csrf
        @if ($module->action == 'edit')
            @method('PUT')
        @endif

        @yield('form')
    </form>

@endsection
