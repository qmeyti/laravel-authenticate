@extends('qlauth::main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('qmauth.Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('qmauth.A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('qmauth.Before proceeding, please check your email for a verification link.') }}
                    {{ __('qmauth.If you did not receive the email') }}, <a href="{{ route('resend_code') }}">{{ __('qmauth.click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
