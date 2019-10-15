@section('scripts')
    <!-- begin::Global Config (global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": [
                        "#c5cbe3",
                        "#a1a8c3",
                        "#3d4465",
                        "#3e4466"
                    ],
                    "shape": [
                        "#f0f3ff",
                        "#d9dffa",
                        "#afb4d4",
                        "#646c9a"
                    ]
                }
            }
        };
    </script>

    <!-- end::Global Config -->

    <!--begin::Global Theme Bundle (used by all pages) -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts (used by this page) -->
    <script src="{{ asset('assets/js/pages/custom/login/login-1.js') }}"></script>
    <script>
        $('#kt_form_status,#kt_form_type').selectpicker();
    </script>

    <!--end::Page Scripts -->

    <script src="{{ asset('js/dashio.min.js') }}?v={{ filemtime(public_path('js/dashio.min.js')) }}"></script>

    @if (file_exists(public_path('js/app.js')))
        <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
    @endif
@show