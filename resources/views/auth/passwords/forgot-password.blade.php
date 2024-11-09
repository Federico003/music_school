@extends('layouts.forgot')


@section('content')
    <div class="fp-box">
        <div class="logo">
            <img src="/images/logo@2x.png" width="100%" alt="{{ config('app.name') }}" />
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('fail'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('fail') }}
                        </div>
                    @endif

                    <div class="msg">
                        Inserire l'indirizzo email usato per la registrazione e ti invieremo una email per effettuale il reset della tua
                        password.
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                        </div>
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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