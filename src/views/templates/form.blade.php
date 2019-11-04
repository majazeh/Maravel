@extends($layouts->mode =='html' ? 'templates.app' : 'templates.app-xhr')

@section('main')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ !isset($user) ? _t('Create') : _t('Edit') }}</h3>
                    </div>
                </div>
                <form method="POST" {!! isset($multipart) ? 'enctype="multipart/form-data"' : '' !!} action="{{ $module->post_action }}" class="kt-form">
                    @csrf
                    @if ($module->action == 'edit')
                        @method('PUT')
                    @endif

                    <div class="kt-portlet__body">
                        @yield('form')
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button class="btn btn-primary">{{ $module->action == 'edit' ? _t('Update') : _t('Create') }}</button>
                            @php
                                if(!isset($cancelLink))
                                {
                                    $cancelLink = null;
                                    if($module->action == 'create' && Route::has("$module->resource.index"))
                                    {
                                        $cancelLink = route("$module->resource.index");
                                    }elseif($module->action == 'edit'){
                                        if(Route::has("$module->resource.show"))
                                        {
                                            $cancelLink = route("$module->resource.show", $id);
                                        }elseif(Route::has("$module->resource.index"))
                                        {
                                            $cancelLink = route("$module->resource.index");
                                        }
                                    }
                                }
                            @endphp
                            @if (isset($cancelLink))
                                <a href="{{$cancelLink}}" class="btn btn-secondary">{{ _t('Cancel') }}</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
