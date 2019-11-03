@extends('templates.body')

@section('main')
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v1" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
                <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url(assets/media/bg/bg-4.jpg);">
                    <div class="kt-grid__item">
                        <a href="#" class="kt-login__logo">
                            <img src="{{ asset('assets/media/logos/logo-4.png') }}" alt="">
                        </a>
                    </div>
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
                        <div class="kt-grid__item kt-grid__item--middle">
                            <h3 class="kt-login__title">{{ _t('Welcome to Metronic!') }}</h3>
                            <h4 class="kt-login__subtitle">{{ _t('The ultimate Bootstrap & Angular 6 admin theme framework for next generation web apps.') }}</h4>
                        </div>
                    </div>
                    <div class="kt-grid__item">
                        <div class="kt-login__info">
                            <div class="kt-login__copyright">&copy 2019 {{ _t('Metronic') }}</div>
                            <div class="kt-login__menu">
                                <a href="#" class="kt-link">{{ _t('Privacy') }}</a>
                                <a href="#" class="kt-link">{{ _t('Legal') }}</a>
                                <a href="#" class="kt-link">{{ _t('Contact') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
                    <div class="kt-login__head">
                        <span class="kt-login__signup-label">{{ _t("Don't have an account yet?") }}</span>&nbsp;&nbsp;
                        <a href="#" class="kt-link kt-login__signup-link">{{ _t('Sign Up!') }}</a>
                    </div>
                    <div class="kt-login__body">
                        <div class="kt-login__form">
                            <div class="kt-login__title">
                                <h3>{{ _t('Sign In') }}</h3>
                            </div>
                            <form class="kt-form" method="POST" novalidate="novalidate" id="kt_login_form">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="{{ _t('Username') }}" name="username" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="{{ _t('Password') }}" name="password" autocomplete="off">
                                </div>
                                <div class="kt-login__actions">
                                    <a href="#" class="kt-link kt-login__link-forgot">{{ _t('Forgot Password?') }}</a>
                                    <button id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">{{ _t('Sign In') }}</button>
                                </div>
                            </form>
                            <div class="kt-login__divider">
                                <div class="kt-divider">
                                    <span></span>
                                    <span>{{ _t('OR') }}</span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="kt-login__options">
                                <a href="#" class="btn btn-primary kt-btn">
                                    <i class="fab fa-facebook-f"></i>
                                    Facebook
                                </a>
                                <a href="#" class="btn btn-info kt-btn">
                                    <i class="fab fa-twitter"></i>
                                    Twitter
                                </a>
                                <a href="#" class="btn btn-danger kt-btn">
                                    <i class="fab fa-google"></i>
                                    Google
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
