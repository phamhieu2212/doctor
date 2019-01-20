@extends('pages.admin.metronic.layout.application',['noFrame' => true] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('libs/metronic/snippets/custom/pages/user/login.js', 'admin') !!}"></script>
@stop

@section('title')
    Signin
@stop

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--signin" id="m_login">
        <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
            <div class="m-stack m-stack--hor m-stack--desktop">
                <div class="m-stack__item m-stack__item--fluid">
                    <div class="m-login__wrapper" style="padding-top: 0;">
                        <div class="m-login__logo">
                            <a href="#">
                                <img src="{!! \URLHelper::asset('libs/metronic/app/media/img//logos/logo-2.png', 'admin') !!}">
                            </a>
                        </div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                                <h3 class="m-login__title">
                                    Đăng nhập vào hệ thống quản trị
                                </h3>
                            </div>
                            <form class="m-login__form m-form" action="{!! action('Admin\AuthController@postSignIn') !!}" method="POST">
                                {!! csrf_field() !!}

                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" placeholder="Tên đăng nhập" name="username" autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
                                </div>
                                <div class="m-login__form-action">
                                    <button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                        Đăng nhập
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url({!! \URLHelper::asset('libs/metronic/app/media/img//bg/bg-4.jpg', 'admin') !!}); padding: 0;">
            <div class="m-grid__item m-grid__item--middle">
                <h3 class="m-login__welcome">
                    KABE
                </h3>
                <p class="m-login__msg">
                    Kabe là ứng dụng di động thuộc quyền sở hữu của công ty CP Kabe,
                    <br>
                    được thành lập và hoạt động theo pháp luật Việt Nam và có giấy chứng nhận đăng ký doanh nghiệp số 0314938520
                </p>
            </div>
        </div>
    </div>
@stop
