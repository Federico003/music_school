<!DOCTYPE html>
<html lang="{{ Str::replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

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
        .email {
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

<body class="email">
    <div class="email-box">
        <div class="logo">
            <img src="/images/logo@2x.png" width="220" alt="{{ config('app.name') }}" />
        </div>
        <div class="card">
            <div class="body">

                <div class="wrapper">
                  <strong style="align-content: center">HAI DIMENTICATO LA PASSWORD ?</strong>
                  <p>Hai inviato una richiesta per cambiare la tua password!</p>
                  <br>
                  <p>Se non hai fatto questa richiesta, ignora questa email. Altrimenti clicca nel bottone qui sotto e cambia la tua password:</p>
                  <div>
                    <a href="{{ route('password.reset', $token) }}">
                        <button type="button" class="btn bg-blue btn-block btn-lg waves-effect">Reset Password</button>
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
