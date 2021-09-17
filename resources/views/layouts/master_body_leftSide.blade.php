<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>

<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">
    <!-- BEGIN:: Aside Menu -->
        <div id="left-sidemenu" fcode="left_sidemenu">
            <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
                <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow " id="menu-highlight">

                    <li class="m-menu__section section_admin hide">
                        <h4 class="m-menu__section-text left_sidemenu_main_menu">@lang('content.MainMenu.MainMenu')</h4>
                    </li>
                    
                    <li class="m-menu__item " aria-haspopup="true" id='section_admin_admin'>
                        <a href="{{ url('/'.$company_id) }}" class="m-menu__link highlight" >
                            <i class="m-menu__link-icon fas fa-desktop"></i>
                            <span class="m-menu__link-text left_sidemenu_my_apps">@lang('content.MainMenu.dashboard')</span>
                        </a>
                    </li>

                    <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover" id='section_admin_admin'>
                        <a href="javascript:;" class="m-menu__link m-menu__toggle">
                            <i class="m-menu__link-icon fa fa-list"></i>
                            <span class="m-menu__link-text left_sidemenu_shopping">@lang('content.MainMenu.Menu')</span>
                            <i class="m-menu__ver-arrow fas fa-angle-right"></i>
                        </a>
                        <div class="m-menu__submenu " m-hidden-height="80" style="display: none; overflow: hidden;">
                            <span class="m-menu__arrow"></span>
                            <ul class="m-menu__subnav">
                                {{--  <li class="m-menu__item " aria-haspopup="true">
                                    <a href="/transaction" class="m-menu__link highlight">
                                        <i class="m-menu__link-icon fa fa-luggage-cart">
                                            <span></span>
                                        </i>
                                        <span class="m-menu__link-text left_sidemenu_delivery_charge_estimation">Transaction</span>
                                    </a>
                                </li>  --}}
                                <li class="m-menu__item" aria-haspopup="true">
                                    <a href="{{ url('storeinfo/'.$company_id) }}" class="m-menu__link highlight">
                                        <i class="m-menu__link-icon fa fa-chart-line">
                                            <span></span>
                                        </i>
                                        <span class="m-menu__link-text left_sidemenu_delivery_charge_estimation">@lang('content.MainMenu.StoreInformation')</span>
                                    </a>
                                </li>
                                <li class="m-menu__item" aria-haspopup="true">
                                    <a href="{{ url('outofstock/'.$company_id) }}" class="m-menu__link highlight">
                                        <i class="m-menu__link-icon fa fa-box-open">
                                            <span></span>
                                        </i>
                                        <span class="m-menu__link-title">
                                            <span class="m-menu__link-wrap">
                                                <span class="m-menu__link-text left_sidemenu_delivery_charge_estimation">@lang('content.MainMenu.OutOfStock')</span>
                                                <span class="m-menu__link-badge">
                                                    <span class="m-badge m-badge--danger">{{countOutOfStock::countOutOfStock($company_id)}}</span>
                                                </span>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--  <li class="m-menu__item " aria-haspopup="true" id='section_admin_admin'>
                        <a href="{{ url('requestedFile/'.$company_id) }}" class="m-menu__link highlight" >
                            <i class="m-menu__link-icon fas fa-file-alt"></i>
                            <span class="m-menu__link-text left_sidemenu_my_apps">Requested File Progress</span>
                        </a>
                    </li>  --}}
                </ul>
            </div>
        </div>

    <!-- END:: Aside Menu -->
</div>