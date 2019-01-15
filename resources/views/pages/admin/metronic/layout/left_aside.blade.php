<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow " style="padding-top: 0;">
            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">
                    MAIN NAVIGATION
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>

            <li class="m-menu__item @if( $menu=='dashboard') m-menu__item--active @endif" aria-haspopup="true">
                <a href="{!! \URL::action('Admin\IndexController@index') !!}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                @lang('admin.menu.dashboard')
                            </span>
                        </span>
                    </span>
                </a>
            </li>


            @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
                <li class="m-menu__section ">
                    <h4 class="m-menu__section-text">
                        USER MANAGEMENT
                    </h4>
                    <i class="m-menu__section-icon flaticon-more-v3"></i>
                </li>

                <li class="m-menu__item @if( $menu=='admin_users') m-menu__item--active @endif" aria-haspopup="true">
                    <a href="{!! \URL::action('Admin\AdminUserController@index') !!}" class="m-menu__link ">
                        <i class="m-menu__link-icon la la-user-secret"></i>
                        <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                                <span class="m-menu__link-text">
                                    @lang('admin.menu.admin_users')
                                </span>
                            </span>
                        </span>
                    </a>
                </li>

            @endif

            @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
                <li class="m-menu__section ">
                    <h4 class="m-menu__section-text">
                        HOSPITAL
                    </h4>
                    <i class="m-menu__section-icon flaticon-more-v3"></i>
                </li>

                <li class="m-menu__item @if( $menu=='hospitals') m-menu__item--active @endif" aria-haspopup="true">
                    <a href="{!! \URL::action('Admin\HospitalController@index') !!}" class="m-menu__link">
                        <i class="m-menu__link-icon la la-sticky-note"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Quản lý bệnh viện
                            </span>
                        </span>
                    </span>
                    </a>
                </li>
                <li class="m-menu__item @if( $menu=='specialties') m-menu__item--active @endif" aria-haspopup="true">
                    <a href="{!! \URL::action('Admin\SpecialtyController@index') !!}" class="m-menu__link">
                        <i class="m-menu__link-icon la la-sticky-note"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Quản lý chuyên khoa
                            </span>
                        </span>
                    </span>
                    </a>
                </li>

            @endif

            @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_ADMIN) )
                <li class="m-menu__section ">
                    <h4 class="m-menu__section-text">
                        HOSPITAL
                    </h4>
                    <i class="m-menu__section-icon flaticon-more-v3"></i>
                </li>

                <li class="m-menu__item @if( $menu=='clinics') m-menu__item--active @endif" aria-haspopup="true">
                    <a href="{!! \URL::action('Admin\ClinicController@index') !!}" class="m-menu__link">
                        <i class="m-menu__link-icon la la-sticky-note"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Quản lý phòng khám
                            </span>
                        </span>
                    </span>
                    </a>
                </li>
            @endif

            @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
                <li class="m-menu__section ">
                    <h4 class="m-menu__section-text">
                        Quản lý cấp bậc
                    </h4>
                    <i class="m-menu__section-icon flaticon-more-v3"></i>
                </li>

                <li class="m-menu__item @if( $menu=='levels') m-menu__item--active @endif" aria-haspopup="true">
                    <a href="{!! \URL::action('Admin\LevelController@index') !!}" class="m-menu__link">
                        <i class="m-menu__link-icon la la-sticky-note"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Quản lý học hàm học vị
                            </span>
                        </span>
                    </span>
                    </a>
                </li>
            @endif

            @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
                <li class="m-menu__section ">
                    <h4 class="m-menu__section-text">
                        BACKEND
                    </h4>
                    <i class="m-menu__section-icon flaticon-more-v3"></i>
                </li>

                <li class="m-menu__item @if( $menu=='oauth_clients') m-menu__item--active @endif" aria-haspopup="true">
                    <a href="{!! \URL::action('Admin\OauthClientController@index') !!}" class="m-menu__link">
                        <i class="m-menu__link-icon la la-key"></i>
                        <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                                <span class="m-menu__link-text">
                                    OAuth Clients
                                </span>
                            </span>
                        </span>
                    </a>
                </li>

            @endif


            <!-- %%SIDEMENU%% -->
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>