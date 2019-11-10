@extends('layouts.index')

@section('users-list')
<thead class="f2">
    <tr>
        <th class="text-center">
            {{ _t('#') }}
            @sort_icon(id)
        </th>
        <th>
            {{ _t('title') }}
        </th>
        <th></th>
    </tr>
</thead>
<tbody class="f1">
    @foreach ($guards as $guard)
    <tr>
        <td class="text-center">
            <a href="{{route($module->resource .'.edit', $guard->serial)}}">{{$guard->serial }}</a>
        </td>
        <td>
            {{ $guard->title }}
        </td>
        <td class="text-center" style="width:150px">
            <div class="d-flex justify-content-around">
                <a class="text-secondary" href="{{route('dashboard.guards.positions.index', $guard->serial)}}">
                    <i class="far fa-shield-alt"></i>
                </a>
                @include('layouts.components.edit-link', ['link' => route($module->resource . '.edit', $guard->serial)])
                @include('layouts.components.delete-link', ['link' => route($module->apiResource . '.destroy', $guard->serial)])
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
@endsection

@section('container-fluid')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        @yield('users-list')
                    </table>
                    {{ $guards->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
