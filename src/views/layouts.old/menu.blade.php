<div class="d-none flex-column d-md-block menu" id="menu">
    @if (false)
        <a class="d-flex justify-content-center align-items-center branding-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo/logo-40.png') }}" alt="{{ $global->title ?: _t('Dashio') }}" width="40" height="40">
        </a>
    @endif

    <a href="{{ route('dashboard') }}" class="branding" title="{{ _t('title.dashio') }}">
        <img src="{{ asset('images/logo/logo-40.png') }}" class="branding-logo" alt="{{ $global->title ?: _t('Dashio') }}" width="40" height="40">
        <div class="branding-title">
            {{ _t('title.dashio') }}
        </div>
    </a>
    <div class="menu-inner">
        @section('menu-itmes')
            <a class="d-flex align-items-center menu-item" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>{{ _t('Dashboard') }}</span>
            </a>
            @if (Guardio::has('user.all'))
                <a class="d-flex align-items-center menu-item" href="{{ route('dashboard.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>{{ _t('Users') }}</span>
                </a>
            @endif

            @if (Guardio::has('guardio.view|guardio.create|guardio.edit|guardio.delete'))
                <a class="d-flex align-items-center menu-item" href="{{ route('dashboard.guards.index') }}">
                    <i class="fas fa-users"></i>
                    <span>{{ _t('Guards') }}</span>
                </a>
            @endif

            @if (Guardio::has('larators'))
                <a class="d-flex align-items-center menu-item" href="{{ route('dashboard.larators.index') }}">
                    <i class="fas fa-users"></i>
                    <span>{{ _t('dashboard.larators') }}</span>
                </a>
            @endif
        @show
    </div>
</div>

<button id="btn-menu" class="d-md-none btn-menu menu-open" type="button"><i class="fas fa-bars"></i></button>
