@extends($layouts->mode  == 'html' ? 'layouts.panel' :  'layouts.panel-xhr')

@section('panel.content')
@include('layouts.create-content')
@endsection
@section('topbar-actions')
<a href="{{ route($module->resource . '.index') }}" class="btn btn-sm btn-info btn-gradient">
    <i class="{{ $module->icons['index'] }}"></i>
    {{ _t($module->resource.'.index') }}
</a>
@if ($module->action == 'edit')
<a href="{{ route($module->resource . '.create') }}" class="btn btn-sm btn-success btn-gradient">
    <i class="{{ $module->icons['create'] }}"></i>
    {{ _t($module->resource.'.create') }}
</a>
@endif

@endsection
