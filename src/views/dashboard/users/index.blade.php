@extends('layouts.index')

@section('users-list')
<thead class="f2">
    <tr>
        <th class="text-center">
            {{ _t('id') }}
            @sort_icon(id)
        </th>
        <th>
            {{ _t('name') }}
            @sort_icon(name)
        </th>
        <th>
            {{ _t('username') }}
            @sort_icon(username)
        </th>
        <th>
            {{ _t('email') }}
        </th>
        <th>
            {{ _t('mobile') }}
        </th>
        <th>
            <select name="status" data-lijax="change" data-state='true'>
                <option {{!request()->status ? 'selected="selected"' : ''}} value="">{{_t('account.status')}}</option>
                @foreach (config('guardio.status', ['awaiting', 'active', 'disable']) as $key => $status)
                    <option {{request()->status == $status ? 'selected="selected"' : ''}} value="{{$status}}">{{_t("user.status.$status")}}</option>
                @endforeach
            </select>
            @sort_icon(status)
        </th>
        <th>
            <select name="type" data-lijax="change" data-state='true'>
                <option {{!request()->type ? 'selected="selected"' : ''}} value="">{{_t('account.type')}}</option>
                @foreach (config('guardio.type', ['admin', 'user']) as $key => $type)
                    <option {{request()->type == $type ? 'selected="selected"' : ''}} value="{{$type}}">{{_t("user.status.$type")}}</option>
                @endforeach
            </select>
            @sort_icon(type)
        </th>
        <th class="text-center">
            <select name="gender" data-lijax="change" data-state='true'>
                <option {{!request()->gender ? 'selected="selected"' : ''}} value="">{{_t('gender')}}</option>
                <option {{request()->gender == 'female' ? 'selected="selected"' : ''}} value="female">{{_t('female')}}</option>
                <option {{request()->gender == 'male' ? 'selected="selected"' : ''}} value="male">{{_t('male')}}</option>
            </select>
            @sort_icon(gender)
        </th>
        <th></th>
    </tr>
</thead>
<tbody class="f1">
    @foreach ($users as $user)
    <tr>
        <td class="text-center">
            <a href="{{route($module->resource .'.show', $user->serial)}}">{{$user->serial }}</a>
        </td>
        <td>
            <a class="text-dark" href="{{ route($module->resource . '.edit', $user->id) }}">{{ $user->name }}</a>
        </td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->mobile }}</td>
        <td>
            {{ _t('user.status.' . $user->status) }}
        </td>
        <td>{{ _t('user.type.' . $user->type) }}</td>
        <td class="text-center">
            <i class="fas fa-{{ $user->gender ?: 'genderless' }} {{ $user->gender == 'male' ? 'text-primary' : ($user->gender == 'female' ? 'text-info' : '')}}"></i>
        </td>
        <td class="text-center">
            @include('layouts.compomnents.edit-link', ['link' => route($module->resource . '.edit', $user->serial ?: $user->id)])
            @include('layouts.compomnents.delete-link', ['link' => route($module->apiResource . '.destroy', $user->serial ?: $user->id)])
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
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
