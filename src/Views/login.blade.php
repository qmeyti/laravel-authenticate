@extends('qlauth::main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('qmauth.Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('signin') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="identifier" class="col-sm-4 col-form-label text-md-right">{{ __('qmauth.identifier') }}</label>

                            <div class="col-md-6">
                                <input id="identifier" type="text" class="form-control{{ $errors->has('identifier') ? ' is-invalid' : '' }}" name="identifier" placeholder="{{implode(', ',$identifiers)}}" value="{{ old('identifier') }}" required autofocus>

                                @if ($errors->has('identifier'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('identifier') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('qmauth.Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('qmauth.Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('qmauth.Login') }}
                                </button>

                                @if (Route::has('recovery_form'))
                                    <a class="btn btn-link" href="{{ route('recovery_form') }}">
                                        {{ __('qmauth.Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
