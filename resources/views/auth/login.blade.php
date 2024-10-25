<!DOCTYPE html>
<html lang="{{ Str::replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Accedi - {{ config('app.name', 'Laravel') }}</title>

    <!-- Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon-->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">

    <!-- Waves Effect Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/node-waves/waves.css') }}">

    <!-- Animation Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/animate-css/animate.css') }}">

    <!-- AdminBSB Theme -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/custom.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/custom.css') }}">
    <style>
        .login-page {
            background-color: #ffffff;
            margin: 10% auto;
            max-width: 400px;
        }

        .logo img {
            width: 100%;
            padding: 10px;
        }

        .logo {
            box-shadow: 0 2px 10px rgb(0 0 0 / 20%);
            background-color: #ffffff;
            text-align: center
        }
    </style>

    <!-- Jquery Core Js -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <img src="/images/logo@2x.png" width="220" alt="{{ config('app.name') }}" />
        </div>
        <div class="card">
            <div class="body">
                @if ($errors->any())
                    <div id="errors-container" class="alert alert-danger alert-dismissible">
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif
                <form id="sign_in" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="msg">
                        <span>Inserisci le tue credenziali</span>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="Email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" style= "width: 90%" name="password" required
                                autocomplete="current-password" placeholder="Password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                <i class="material-icons"  id="hide" onclick="ShowHide()" hidden="hidden"  style="float: right">visibility_off</i>
                                <i class="material-icons"  id="show" onclick="ShowHide()"  style="float: right">visibility</i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input class="filled-in chk-col-blue" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">{{ __('Ricordami') }}</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn waves-effect btn-block btn-primary waves-effect" type="submit">
                                {{ __('Accedi') }}
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="/register">{{ __('Registrati Ora!') }}</a>
                        </div>

                        <div>
                            <a href="{{ route('password.forgot')}}"  style="color: gray">{{ __('Hai dimenticato la password?') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('plugins/node-waves/waves.js') }}"></script>

    <!-- Jquery Validation Plugin -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>

    <script src="{{ asset('js/admin/admin.js') }}"></script>

    <!-- Js for ShowHide function -->
    <script type="text/javascript">
        function ShowHide (){
            var x = document.getElementById("password");
            if (x.type === "password"){
                x.type = "text";
                document.getElementById('hide').style.display = "inline-block";
                document.getElementById('show').style.display = "none";
            }
            else{
                x.type = "password";
                document.getElementById('hide').style.display = "none";
                document.getElementById('show').style.display = "inline-block";
            }
        }
    </script>

</body>

</html>
