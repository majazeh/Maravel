<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav ">
                @section('menus')
                    <li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true">
                        <a href="{{route('dashboard')}}" class="kt-menu__link ">
                            <span class="kt-menu__link-icon">
                                <img src="{{ asset('media/icons/svg/Design/Layers.svg') }}" alt="">
                            </span>
                            <span class="kt-menu__link-text">{{_t('Dashboard')}}</span>
                        </a>
                    </li>

                    <li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true">
                        <a href="{{route('dashboard.users.index')}}" class="kt-menu__link ">
                            <span class="kt-menu__link-icon">
                                <img src="{{ asset('media/icons/svg/General/User.svg') }}" alt="">
                            </span>
                            <span class="kt-menu__link-text">{{_t('dashboard.users')}}</span>
                        </a>
                    </li>
                    @if (false)
                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">Two Levels</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>

                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon">
                                    <img src="{{ asset('media/icons/svg/Shopping/Box%232.svg') }}" alt="">
                                </span>
                                <span class="kt-menu__link-text">Base</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Base</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item " aria-haspopup="true">
                                        <a href="components/base/colors.html" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">State Colors</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="kt-menu__section ">
                            <h4 class="kt-menu__section-text">Three Levels</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>

                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon">
                                    <img src="{{ asset('media/icons/svg/Layout/Layout-4-blocks.svg') }}" alt="">
                                </span>
                                <span class="kt-menu__link-text">Applications</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu ">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Applications</span>
                                        </span>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i>
                                            <span class="kt-menu__link-text">Users</span>
                                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                        </a>
                                        <div class="kt-menu__submenu ">
                                            <span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item " aria-haspopup="true">
                                                    <a href="custom/apps/user/list-default.html" class="kt-menu__link ">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                        <span class="kt-menu__link-text">List - Default</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                @show
            </ul>
        </div>
    </div>
</div>
