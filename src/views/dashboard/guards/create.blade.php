@extends('layouts.create')

@section('form')
<div class="form-group">
    <label for="title">{{ _t('title') }}</label>
    <div class="form-input">
        <input class="form-control" type="title" name="title" id="title" placeholder="{{ _t('title') }}" value="{{ isset($guard->title) ? $guard->title : ''}}">
        <label class="form-icon" for="title"><i class="fas fa-envelope"></i></label>
    </div>
</div>

@endsection
