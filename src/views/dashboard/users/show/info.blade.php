<div class="kt-portlet">
    <div class="kt-portlet__body">
        <div class="kt-widget kt-widget--user-profile-1 pb-0">
            <div class="kt-widget__head">
                <div class="kt-widget__media">
                    <img src="{{ asset('assets/media/users/100_13.jpg') }}" alt="{{ _t('Profile Image') }}">
                </div>
                <div class="kt-widget__content">
                    <div class="kt-widget__section">
                        <a href="#" class="kt-widget__username">{{ $user->name }}</a>
                        <span class="kt-widget__subtitle">{{ $user->username }}</span>
                    </div>
                </div>
            </div>
            <div class="kt-widget__body">
                <div class="kt-widget__content">
                    <div class="kt-widget__info">
                        <span class="kt-widget__label">{{ _t('Email') }}</span>
                        <a href="#" class="kt-widget__data">{{ $user->email }}</a>
                    </div>
                    <div class="kt-widget__info">
                        <span class="kt-widget__label">{{ _t('Mobile') }}</span>
                        <a href="tel:+{{$user->mobile}}" class="kt-widget__data d-inline-block direction-ltr text-dark direct">{{ $user->mobileText }}</a>
                    </div>
                </div>
                <div class="kt-widget__items">
                    <a href="#" class="kt-widget__item kt-widget__item--active">
                        <span class="kt-widget__section">
                            <span class="kt-widget__icon">
                                <img src="{{ asset('media/icons/svg/Design/Layers.svg') }}" alt="">
                            </span>
                            <span class="kt-widget__desc">{{ _t('Profile Overview') }}</span>
                        </span>
                    </a>
                    <a href="#" class="kt-widget__item">
                        <span class="kt-widget__section">
                            <span class="kt-widget__icon">
                                <img src="{{ asset('media/icons/svg/Design/Layers.svg') }}" alt="">
                            </span>
                            <span class="kt-widget__desc">{{ _t('Profile Overview') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
