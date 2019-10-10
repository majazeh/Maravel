<div class="{{'col-12 col-md-8 col-xl-5 mx-auto' }}">
    <form method="POST" {!! isset($multipart) ? 'enctype="multipart/form-data"' : '' !!} action="{{ $module->post_action}}">
        @csrf
        @if ($module->action == 'edit')
            @method('PUT')
        @endif

        @yield('form')

        <div>
            <button type="submit" class="btn btn-{{ $module->action == 'edit' ? 'primary' : 'success' }} btn-gradient btn-action">
                @if ($module->action == 'edit')
                    <i class="fas fa-save"></i>
                @else
                    <i class="fas fa-check"></i>
                @endif
                {{ $module->header }}
            </button>
        </div>
    </form>
</div>
