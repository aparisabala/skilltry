@extends('admin.layouts.full-page-layout',["tabTitle" => config('i.service_name').' | '.pxLang($data['lang'],'breadCum.title') ])
@section('page')
<div class="auth-split auth-signin">
    <aside class="auth-brand-panel">
        <a href="{{ url('admin/login') }}" class="auth-brand-logo">
            <img src="{{ config('i.logo') }}" alt="{{ config('i.service_name') }}">
        </a>
        <div class="auth-brand-content">
            <span class="auth-feature-icon"><i class="bx bx-log-in-circle" aria-hidden="true"></i></span>
            <p class="auth-eyebrow">{{ config('i.service_name') }}</p>
            <h1>{{ __('Your workspace, connected.') }}</h1>
            <p>{{ __('People, roles and everyday work. Together in one place.') }}</p>
        </div>
        <div class="auth-brand-footer"><i class="bx bx-shield-quarter" aria-hidden="true"></i> {{ __('Administration workspace') }}</div>
    </aside>
    <main class="auth-form-panel">
        <div class="auth-form-content">
            @include('errors.fragments.error-view-bs5')
            <div class="auth-form-icon"><i class="bx bx-log-in-circle" aria-hidden="true"></i></div>
            <div >
                <h2 class="auth-form-title">{{ pxLang($data['lang'],'pageTitle') }}</h2>
                <p class="text-muted mb-4">{{ __('Enter your details to continue to your account.') }}</p>
                <form id="frmAdminUserLogin" class="mb-3" autocomplete="off" method="POST">
                                <div class="mb-3">
                                    <label class="form-label"><b> {{pxLang($data['lang'],'fields.email')}} </b> <em class="required">*</em> <span id="email_error"> </span> </label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="{{pxLang($data['lang'],'fields.email_palceholder')}}">
                                </div>
                                <div class="mb-3">
                                    <div class="float-end">
                                        <a href="{{ url('admin/reset')}}" class="text-muted">{{pxLang($data['lang'],'fields.forgot_password')}}</a>
                                    </div>
                                    <label class="form-label"><b> {{pxLang($data['lang'],'fields.password')}} </b> <em class="required">*</em> <span id="password_error"> </span> </label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="{{pxLang($data['lang'],'fields.password_palceholder')}}">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                    <label class="form-check-label" for="auth-remember-check">{{pxLang($data['lang'],'fields.remember')}}</label>
                                </div>
                                <div class="mt-3 text-end">
                                    <button class="btn btn-primary w-sm waves-effect waves-light disBtn" disabled type="submit">{{pxLang($data['lang'],'fields.btn_login')}}</button>
                                </div>
                            </form>
            </div>
            
        </div>
    </main>
</div>
@endsection
