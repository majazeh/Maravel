@extends('layouts.index')

@section('container-fluid')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    {{$user->id}} - {{$user->name}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
