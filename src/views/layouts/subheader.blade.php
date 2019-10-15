@section('subheader')
    <div class="kt-subheader  kt-grid__item" id="kt_subheader" data-xhr="subheader">
        <div class="kt-container  kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ $module->header ?: _t('Page Title') }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    @if (Breadcrumbs::exists(\Request::route()->getName()))
                        {{ Breadcrumbs::render(\Request::route()->getName(), get_defined_vars()) }}
                    @endif
                </div>
                @if (false)
                <div class="kt-input-icon kt-input-icon--right kt-subheader__search">
                    <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
                    <span class="kt-input-icon__icon kt-input-icon__icon--right">
                        <span>
                            <i class="flaticon2-search-1"></i>
                        </span>
                    </span>
                </div>
                @endif
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper" data-xhr="subheader-tools">
                    @section('subheader-tools')
                    @if (\Route::has($module->resource . '.create') && \Route::current()->getAction('as') != $module->resource . '.create')
                        <a href="{{route($module->resource . ".create")}}" class="btn btn-label-primary btn-bold btn-sm btn-icon-h kt-margin-l-10">{{_t($module->resource . ".create")}}</a>
                    @endif
                    @show
                </div>
            </div>
        </div>
    </div>
@show

