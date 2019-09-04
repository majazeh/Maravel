@extends($layouts->mode == 'html' ? 'layouts.panel' : 'layouts.panel-xhr')

@section('panel.content')
@include('layouts.index-content')
@endsection

@section('topbar-actions')
	@if (Route::has($module->resource . '.create'))
		<a href="{{ route($module->resource . '.create') }}" class="btn btn-sm btn-success btn-gradient">
			<i class="{{ $module->icons['create'] }}"></i>
			{{ _t($module->resource . '.create') }}
		</a>
	@endif
@endsection
