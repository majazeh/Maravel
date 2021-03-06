@extends('templates.app')

@section('main')

<div class="kt-portlet">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-users-1"></i>
            </span>
            <h3 class="kt-portlet__head-title">{{ _t('Users') }}</h3>
        </div>
        @if (true)
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <div class="dropdown dropdown-inline">
                            <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="la la-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="kt-nav">
                                    <li class="kt-nav__section kt-nav__section--first">
                                        <span class="kt-nav__section-text">Choose an option</span>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-print"></i>
                                            <span class="kt-nav__link-text">Print</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-copy"></i>
                                            <span class="kt-nav__link-text">Copy</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                            <span class="kt-nav__link-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-file-text-o"></i>
                                            <span class="kt-nav__link-text">CSV</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-file-pdf-o"></i>
                                            <span class="kt-nav__link-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        &nbsp;
                        <a href="#" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            {{ _t('New User') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="kt-portlet__body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ _t('#') }}</th>
                    <th>{{ _t('Name') }}</th>
                    <th>{{ _t('Username') }}</th>
                    <th>{{ _t('Email') }}</th>
                    <th>{{ _t('Mobile') }}</th>
                    <th>{{ _t('Status') }}</th>
                    <th>{{ _t('Type') }}</th>
                    <th>{{ _t('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            @include('layouts.components.serial-link', ['row' => $user])

                        </td>
                        <td>
                            @include('dashboard.users.td.displayname')
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @include('dashboard.users.td.mobile')
                        </td>
                        <td>
                            @include('dashboard.users.td.status')
                        </td>
                        <td>
                            @include('dashboard.users.td.type')
                        </td>
                        <td style="width: 150px;">
                            @include('layouts.components.show-link', ['link' => route($module->resource . '.show', $user->serial ?: $user->id)])
                            @include('layouts.components.edit-link', ['link' => route($module->resource . '.edit', $user->serial ?: $user->id)])
                            @include('layouts.components.delete-link', ['link' => route($module->apiResource . '.destroy', $user->serial ?: $user->id)])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>

@endsection
