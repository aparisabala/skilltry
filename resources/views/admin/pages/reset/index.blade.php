@extends('admin.layouts.full-page-layout',["tabTitle" => config('i.service_name').' | '.pxLang($data['lang'],'breadCum.title') ])
@section('page')
<div class="auth-split auth-recovery">
    <aside class="auth-brand-panel">
        <a href="{{ url('admin/login') }}" class="auth-brand-logo">
            <img src="{{ config('i.logo') }}" alt="{{ config('i.service_name') }}">
        </a>
        <div class="auth-brand-content">
            <span class="auth-feature-icon"><i class="bx bx-key" aria-hidden="true"></i></span>
            <p class="auth-eyebrow">{{ config('i.service_name') }}</p>
            <h1>{{ __('A fresh start.') }}</h1>
            <p>{{ __('Recover access to your account and get back to your workspace.') }}</p>
        </div>
        <div class="auth-brand-footer"><i class="bx bx-shield-quarter" aria-hidden="true"></i> {{ __('Administration workspace') }}</div>
    </aside>
    <main class="auth-form-panel">
        <div class="auth-form-content">
            
            <div class="auth-form-icon"><i class="bx bx-key" aria-hidden="true"></i></div>
            <div id="resetBase">
                <h2 class="auth-form-title">{{ pxLang($data['lang'],'pageTitle') }}</h2>
                <p class="text-muted mb-4">{{ __('Enter your email to receive a recovery code.') }}</p>
                <form id="frmSendAdminUserCode" class="mb-3" autocomplete="off" method="POST">
                                <div class="mb-3">
                                    <label class="form-label"><b> {{pxLang($data['lang'],'fields.email')}}  </b> <em class="required">*</em> <span id="email_error"> </span> </label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="{{pxLang($data['lang'],'fields.email_palceholder')}}">
                                </div>
                                <div class="mt-3 text-end">
                                    <button class="btn btn-primary w-sm waves-effect waves-light disBtn" disabled type="submit">{{pxLang($data['lang'],'btns.send_code')}}</button>
                                </div>
                            </form>
            </div>
            <a href="{{ url('admin/login') }}" class="auth-back-link"><i class="bx bx-arrow-back" aria-hidden="true"></i> {{ __('Back to login') }}</a>
        </div>
    </main>
</div>
@endsection
