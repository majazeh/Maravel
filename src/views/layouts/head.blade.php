<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="description" content="{{ $global->title ?: _t('App Description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="api-token" content="{{ session('api-token') }}">

@section('head-styles')
    <!--begin::Fonts -->
    <!--end::Fonts -->

    <!--begin::Page Vendors Styles (used by this page) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.rtl.css') }}">

    <!--end::Page Vendors Styles -->

    <!--begin::Global Theme Styles (used by all pages) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.rtl.css') }}">

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins (used by all pages) -->
    <link rel="stylesheet" href="{{ asset('assets/css/skins/header/base/light.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skins/header/menu/light.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skins/brand/dark.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skins/aside/dark.rtl.css') }}">

    <!--end::Layout Skins -->

    @if (file_exists(public_path('css/app.css')))
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @endif

    @if (file_exists(public_path('media/logos/favicon.ico')))
        <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}?v={{ filemtime(public_path('media/logos/favicon.ico')) }}" type="image/x-icon">
    @endif

    @if (file_exists(public_path('media/logos/apple-touch-icon.png')))
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/logos/apple-touch-icon.png') }}?v={{ filemtime(public_path('media/logos/apple-touch-icon.png')) }}">
    @endif

    @if (file_exists(public_path('media/logos/favicon-32x32.png')))
		<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('media/logos/favicon-32x32.png') }}?v={{ filemtime(public_path('media/logos/favicon-32x32.png')) }}">
	@endif

	@if (file_exists(public_path('media/logos/favicon-16x16.png')))
		<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('media/logos/favicon-16x16.png') }}?v={{ filemtime(public_path('media/logos/favicon-16x16.png')) }}">
	@endif

	@if (file_exists(public_path('site.webmanifest')))
	    <link rel="manifest" href="/site.webmanifest">
	@endif

	@if (file_exists(public_path('media/logos/safari-pinned-tab.svg')))
		<link rel="mask-icon" href="{{ asset('media/logos/safari-pinned-tab.svg') }}?v={{ filemtime(public_path('media/logos/safari-pinned-tab.svg')) }}" color="#ffde00">
	@endif

	<meta name="msapplication-TileColor" content="#ffde00">
	<meta name="theme-color" content="#ffde00">
@show

<title>{{ $global->title ?: _t('App Title') }}</title>