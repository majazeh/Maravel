<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Order Statistics
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="#" class="btn btn-label-brand btn-bold btn-sm dropdown-toggle" data-toggle="dropdown">Export</a>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                <ul class="kt-nav">
                    <li class="kt-nav__head">
                        Export Options
                        <span data-toggle="kt-tooltip" data-placement="right" title="{{ _t('Click to learn more...') }}">
                            <img src="{{ asset('media/icons/svg/Code/Info-circle.svg') }}" alt="">
                        </span>
                    </li>
                    <li class="kt-nav__separator"></li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <i class="kt-nav__link-icon flaticon2-drop"></i>
                            <span class="kt-nav__link-text">Activity</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <i class="kt-nav__link-icon flaticon2-calendar-8"></i>
                            <span class="kt-nav__link-text">FAQ</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <i class="kt-nav__link-icon flaticon2-telegram-logo"></i>
                            <span class="kt-nav__link-text">Settings</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <i class="kt-nav__link-icon flaticon2-new-email"></i>
                            <span class="kt-nav__link-text">Support</span>
                            <span class="kt-nav__link-badge">
                                <span class="kt-badge kt-badge--success kt-badge--rounded">5</span>
                            </span>
                        </a>
                    </li>
                    <li class="kt-nav__separator"></li>
                    <li class="kt-nav__foot">
                        <a class="btn btn-label-danger btn-bold btn-sm" href="#">Upgrade plan</a>
                        <a class="btn btn-clean btn-bold btn-sm" href="#" data-toggle="kt-tooltip" data-placement="right" title="Click to learn more...">Learn more</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-widget12">
            <div class="kt-widget12__content">
                <div class="kt-widget12__item">
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">Annual Taxes EMS</span>
                        <span class="kt-widget12__value">$400,000</span>
                    </div>
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">Finance Review Date</span>
                        <span class="kt-widget12__value">July 24,2019</span>
                    </div>
                </div>
                <div class="kt-widget12__item">
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">Avarage Revenue</span>
                        <span class="kt-widget12__value">$60M</span>
                    </div>
                    <div class="kt-widget12__info">
                        <span class="kt-widget12__desc">Revenue Margin</span>
                        <div class="kt-widget12__progress">
                            <div class="progress kt-progress--sm">
                                <div class="progress-bar kt-bg-brand" role="progressbar" style="width: 40%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="kt-widget12__stat">40%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-widget12__chart" style="height:250px;">
                <canvas id="kt_chart_order_statistics"></canvas>
            </div>
        </div>
    </div>
</div>