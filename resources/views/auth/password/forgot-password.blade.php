@extends('layouts.forgot')


@section('content')
    <div class="fp-box">
        <div class="logo">
            <img src="/images/logo@2x.png" width="100%" alt="{{ config('app.name') }}" />
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" method="POST" action="{{ route('password.resetEmail') }}">
                    @csrf

                    <div class="msg">
                        Inserire l'indirizzo email usato per la registrazione e ti invieremo una email per effettuale il reset della tua
                        password.
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <x-input type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required autofocus/>
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-blue waves-effect" type="submit">{{ __('Reset Password !') }}</button>

                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="{{ route('login') }}">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



