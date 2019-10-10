<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
    <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
        <ul class="kt-menu__nav ">
            @section('header-menus')
                <li class="kt-menu__item  kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open kt-menu__item--here kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-text">Pages</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true">
                                <a href="index.html" class="kt-menu__link ">
                                    <span class="kt-menu__link-icon">
                                        <img src="{{ asset('media/icons/svg/Clothes/Briefcase.svg') }}" alt="">
                                    </span>
                                    <span class="kt-menu__link-text">My Account</span>
                                </a>
                            </li>
                            <li class="kt-menu__item " aria-haspopup="true">
                                <a href="javascript:;" class="kt-menu__link ">
                                    <span class="kt-menu__link-icon">
                                        <img src="{{ asset('media/icons/svg/Code/Compiling.svg') }}" alt="">
                                    </span>
                                    <span class="kt-menu__link-text">Task Manager</span>
                                    <span class="kt-menu__link-badge">
                                        <span class="kt-badge kt-badge--success kt-badge--rounded">2</span>
                                    </span>
                                </a>
                            </li>
                            <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <span class="kt-menu__link-icon">
                                        <img src="{{ asset('media/icons/svg/Code/CMD.svg') }}" alt="">
                                    </span>
                                    <span class="kt-menu__link-text">Team Manager</span>
                                    <i class="kt-menu__hor-arrow la la-angle-right"></i>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right">
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item " aria-haspopup="true">
                                            <a href="javascript:;" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="kt-menu__link-text">Add Team Member</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                                <a href="#" class="kt-menu__link kt-menu__toggle">
                                    <span class="kt-menu__link-icon">
                                        <img src="{{ asset('media/icons/svg/Communication/Mail-box.svg') }}" alt="">
                                    </span>
                                    <span class="kt-menu__link-text">Projects Manager</span>
                                    <i class="kt-menu__hor-arrow la la-angle-right"></i>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--right">
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item " aria-haspopup="true">
                                            <a href="javascript:;" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--line">
                                                    <span></span>
                                                </i>
                                                <span class="kt-menu__link-text">Latest Projects</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-text">Features</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu  kt-menu__submenu--fixed kt-menu__submenu--left" style="width:1000px">
                        <div class="kt-menu__subnav">
                            <ul class="kt-menu__content">
                                <li class="kt-menu__item ">
                                    <h3 class="kt-menu__heading kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Task Reports</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </h3>
                                    <ul class="kt-menu__inner">
                                        <li class="kt-menu__item " aria-haspopup="true">
                                            <a href="javascript:;" class="kt-menu__link ">
                                                <span class="kt-menu__link-icon">
                                                    <img src="{{ asset('media/icons/svg/Clothes/Briefcase.svg') }}" alt="">
                                                </span>
                                                <span class="kt-menu__link-text">Latest Tasks</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="kt-menu__item ">
                                    <h3 class="kt-menu__heading kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Profit Margins</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </h3>
                                    <ul class="kt-menu__inner">
                                        <li class="kt-menu__item " aria-haspopup="true">
                                            <a href="javascript:;" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--line">
                                                    <span></span>
                                                </i>
                                                <span class="kt-menu__link-text">Overall Profits</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="kt-menu__item ">
                                    <h3 class="kt-menu__heading kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Staff Management</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </h3>
                                    <ul class="kt-menu__inner">
                                        <li class="kt-menu__item " aria-haspopup="true">
                                            <a href="javascript:;" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="kt-menu__link-text">Top Management</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="kt-menu__item ">
                                    <h3 class="kt-menu__heading kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Tools</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </h3>
                                    <ul class="kt-menu__inner">
                                        <li class="kt-menu__item " aria-haspopup="true">
                                            <a href="javascript:;" class="kt-menu__link ">
                                                <span class="kt-menu__link-text">Analytical Reports</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            @show
        </ul>
    </div>
</div>