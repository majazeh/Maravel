
@extends('layouts.index')

@section('users-list')
<thead class="f2">
    <tr>
        <th>
            {{ _t('gate') }}
        </th>
        <th>
            {{ _t('value') }}
        </th>
        <th>
            {{ _t('description') }}
        </th>
        <th></th>
    </tr>
</thead>
<tbody class="f1">
    @foreach ($guardpositions as $position)
    <tr>
        <td>
            {{ $position->gate }}
        </td>
        <td>{{ $position->value }}</td>
        <td>{{ $position->description }}</td>
        <td class="text-center">
            @if ($position->serial)
                @include('layouts.compomnents.delete-link', ['link' => route('api.guards.positions.destroy', [$parent->serial, $position->gate])])
            @else
                <a class="text-secondary" data-lijax="click" href="{{route('api.guards.positions.store', $parent->serial)}}" data-method="POST" data-name="gate" data-value="{{$position->gate}}">
                    <i class="far fa-shield-check"></i>
                </a>
            @endif
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
