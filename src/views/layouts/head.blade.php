<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="description" content="{{ $global->title ?: _t('App Description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="api-token" content="{{ session('api-token') }}">

@include('layouts.head-styles')

<title>{{ $global->title ?: _t('App Title') }}</title>
