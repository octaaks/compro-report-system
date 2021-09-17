<header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">

            <!-- BEGIN:: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-light ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle"
                            class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <img alt="" src="https://d181jp1bcm67qh.cloudfront.net/images/sideMenu.svg" width="30">
                        </a>
                        <a href="/" class="m-brand__logo-wrapper d-none d-sm-inline">
                            <img alt="" src="https://d181jp1bcm67qh.cloudfront.net/images/comprohijau-full.png"
                                width="120">
                        </a>
                        <a href="/" class="m-brand__logo-wrapper d-inline d-sm-none">
                            <img alt="" src="https://d181jp1bcm67qh.cloudfront.net/images/comprohijau-full.png"
                                width="100">
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <!-- BEGIN:: Left Aside Minimize Toggle -->
                        <a id="m_aside_left_minimize_toggle"
                            class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                            <span></span>
                        </a>
                        <!-- END:: -->

                        <!-- BEGIN:: Responsive Header Menu Toggler -->
                        <a style="display:none !important" id="m_aside_header_menu_mobile_toggle" href="javascript:;"
                            class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                        <!-- END:: -->

                        <!-- BEGIN:: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;"
                            class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <!-- BEGIN:: Topbar Toggler -->
                    </div>
                </div>
            </div>
            <!-- END:: Brand -->

            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                <!-- BEGIN:: Horizontal Menu -->
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark "
                    id="m_aside_header_menu_mobile_close_btn">
                    <i class="la la-close"></i>
                </button>
                <div id="m_header_menu"
                    class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow "></ul>
                </div>
                <!-- END:: Horizontal Menu -->

                <!-- BEGIN:: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                m-dropdown-toggle="click" aria-expanded="true" style="height:75%;">
                                <a href="#" class="m-nav__link m-dropdown__toggle" style="float:left;">
                                    <span class="m-topbar__username m-badge m-badge--brand m-badge--wide"
                                        style="background-color: #77AC1D; color: #FFFFFF; height:75%">user@gmail.com</span>
                                </a>
                            </li>
                            <li class="m-nav__item m-topbar__languages m-dropdown m-dropdown--small m-dropdown--arrow m-dropdown--align-right m-dropdown--mobile-full-width"
                                m-dropdown-toggle="click" aria-expanded="true">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-nav__link-text">
                                        <img id = 'flag-language' class="m-topbar__language-selected-img circle"
                                            src="https://d181jp1bcm67qh.cloudfront.net/images/flag/en.svg">
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper" style="z-index: 101;">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                        style="left: auto; right: 5px;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center">
                                            <span class="m-dropdown__header-subtitle">@lang('content.MainMenu.SelectYourLanguage')</span>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('change-language', ['locale' => LocaleEnum::EN]) }}"
                                                            class="m-nav__link">
                                                            <span class="m-nav__link-icon">
                                                                <img class="m-topbar__language-img"
                                                                    src="https://d181jp1bcm67qh.cloudfront.net/images/flag/en.svg">
                                                            </span>
                                                            <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">{{LocaleEnum::getString(LocaleEnum::EN)}}</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item ">
                                                        <a href="{{ route('change-language', ['locale' => LocaleEnum::ID]) }}"
                                                            class="m-nav__link ">
                                                            <span class="m-nav__link-icon">
                                                                <img class="m-topbar__language-img"
                                                                    src="https://d181jp1bcm67qh.cloudfront.net/images/flag/id.svg">
                                                            </span>
                                                            <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">{{LocaleEnum::getString(LocaleEnum::ID)}}</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item ">
                                                        <a href="{{ route('change-language', ['locale' => LocaleEnum::ZHHANZ]) }}"
                                                            class="m-nav__link ">
                                                            <span class="m-nav__link-icon">
                                                                <img class="m-topbar__language-img"
                                                                    src="https://d181jp1bcm67qh.cloudfront.net/images/flag/zh-Hans.svg">
                                                            </span>
                                                            <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">{{LocaleEnum::getString(LocaleEnum::ZHHANZ)}}</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item ">
                                                        <a href="{{ route('change-language', ['locale' => LocaleEnum::KM]) }}"
                                                            class="m-nav__link ">
                                                            <span class="m-nav__link-icon">
                                                                <img class="m-topbar__language-img" style="height:22px;"
                                                                    src="https://d181jp1bcm67qh.cloudfront.net/images/flag/km.png">
                                                            </span>
                                                            <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">{{LocaleEnum::getString(LocaleEnum::KM)}}</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item ">
                                                        <a href="{{ route('change-language', ['locale' => LocaleEnum::FR]) }}"
                                                            class="m-nav__link ">
                                                            <span class="m-nav__link-icon">
                                                                <img class="m-topbar__language-img"
                                                                    src="https://d181jp1bcm67qh.cloudfront.net/images/flag/fr.svg">
                                                            </span>
                                                            <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">{{LocaleEnum::getString(LocaleEnum::FR)}}</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item ">
                                                        <a href="{{ route('change-language', ['locale' => LocaleEnum::ES]) }}"
                                                            class="m-nav__link ">
                                                            <span class="m-nav__link-icon">
                                                                <img class="m-topbar__language-img"
                                                                    src="https://d181jp1bcm67qh.cloudfront.net/images/flag/es.svg">
                                                            </span>
                                                            <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">{{LocaleEnum::getString(LocaleEnum::ES)}}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </li>

                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                m-dropdown-toggle="click">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-nav__link-icon">
                                        <i class="fas fa-th"></i>
                                        {{--  <i class="flaticon-grid-menu"></i>  --}}
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                        style="color:white;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__item">
                                                        <a href="https://dev-engine.compro.co.id/change-password"
                                                            class="m-nav__link">
                                                            <i class="m-nav__link-icon la la-key"></i>
                                                            <span class="m-nav__link-text">@lang('content.MainMenu.ChangePassword')</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit">
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="#"
                                                            class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder"
                                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">@lang('content.MainMenu.Logout')</a>
                                                        <form id="logout-form"
                                                            action="https://dev-engine.compro.co.id/logout"
                                                            method="POST" style="display: none;">
                                                            <input type="hidden" name="_token"
                                                                value="7TAoPuEOZHqarHZYFWukD1rbPfKqk5tI3qDKZRDT">
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END:: Topbar -->
            </div>
        </div>
    </div>
</header>