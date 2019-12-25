@extends('qlauth::main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('qmauth.Verify Your Mobile Number') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('qmauth.A fresh verification code has been sent to your mobile.') }}
                            </div>
                        @endif
                            <form action="{{route('verification')}}" method="get" class="form-horizontal form-label-left">
                                <div class="form-group row">
                                    <label for="code" class="col-md-4 col-form-label text-md-right">{{__('qmauth.Activation code')}}</label>
                                    <div class="col-md-6">
                                        <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" required autofocus>
                                        <input type="hidden" name="mode" value="mobile" >
                                        @if ($errors->has('code'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12 col-lg-8 offset-lg-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('qmauth.Activation') }}
                                        </button>

                                        <a href="{{ route("resend_code") }}" id="resend-code" class="btn btn-success">
                                            {{ __('qmauth.Resend Code') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
