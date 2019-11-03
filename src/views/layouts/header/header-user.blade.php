<div class="kt-header__topbar-item kt-header__topbar-item--user">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
        <div class="kt-header__topbar-user">
            <span class="kt-header__topbar-username kt-hidden-mobile">{{auth()->user()->name}}</span>
            @isset(auth()->user()->avatar['150x'])
                <img alt="{{auth()->user()->name}}" src="{{auth()->user()->avatar['150x']->url}}" />
            @else
                <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">
                    {{mb_substr(auth()->user()->name, 0, 1)}}
                </span>
            @endisset
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

        <!--begin: Head -->
        <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ asset('assets/media/misc/bg-1.jpg') }})">
            <div class="kt-user-card__avatar">
                @isset(auth()->user()->avatar['150x'])
                    <img alt="{{auth()->user()->name}}" src="{{auth()->user()->avatar['150x']->url}}" />
                @else
                    <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">
                        {{mb_substr(auth()->user()->name, 0, 1)}}
                    </span>
                @endisset
            </div>
            <div class="kt-user-card__name">{{auth()->user()->name}}</div>
            @if (false)
                <div class="kt-user-card__badge">
                    <span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>
                </div>
            @endif
        </div>

        <!--end: Head -->

        <!--begin: Navigation -->
        <div class="kt-notification">
            <a href="{{route('dashboard.users.show', auth()->user()->serial ?: auth()->user()->id)}}" class="kt-notification__item">
                <div class="kt-notification__item-icon">
                    <i class="flaticon2-calendar-3 kt-font-success"></i>
                </div>
                <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title kt-font-bold">{{_t('My Profile')}}</div>
                    <div class="kt-notification__item-time">{{_t('Account settings and more')}}</div>
                </div>
            </a>
            <div class="kt-notification__custom kt-space-between">
                <a href="{{route('logout')}}" class="btn btn-label btn-label-brand btn-sm btn-bold" data-lijax="click" data-method="POST">logout</a>
            </div>
        </div>

        <!--end: Navigation -->
    </div>
</div>
