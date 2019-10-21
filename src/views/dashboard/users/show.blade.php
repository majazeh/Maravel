@extends('templates.app')

@section('main')

<div class="row">
    <div class="col-3">
        @include('dashboard.users.show.info')
    </div>
    <div class="col-9">
        <div class="row">
            <div class="col-6">
                @include('dashboard.users.show.order')
            </div>
            <div class="col-6">
                @include('dashboard.users.show.tasks')
            </div>
        </div>
    </div>
</div>

@endsection