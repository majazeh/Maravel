@extends('templates.form')

@section('form')
    @include('dashboard.users.forms.avatar')
    @include('dashboard.users.forms.name')
    @include('dashboard.users.forms.username')
    @include('dashboard.users.forms.email')
    @include('dashboard.users.forms.mobile')
    @include('dashboard.users.forms.password')
    @include('dashboard.users.forms.status')
    @include('dashboard.users.forms.type')
    @include('dashboard.users.forms.gender')
    @include('dashboard.users.forms.groups')
@endsection
