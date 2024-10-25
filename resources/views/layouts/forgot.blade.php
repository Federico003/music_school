<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Forgot Password - {{ config('app.name', 'Laravel') }}</title>

    <!-- Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Sweetalert Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">

    <!-- Morris Chart Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/morrisjs/morris.css') }}">

    <!-- JQuery DataTable Css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}">

    <!-- Bootstrap Select Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.css') }}">

    <!-- Multi Select Css -->
    <link href="{{ asset('plugins/multi-select/css/multi-select.css') }}" rel="stylesheet">

    <!-- Dropzone Css -->
    <link href="{{ asset('plugins/dropzone/dropzone.css') }}" rel="stylesheet">

    <!-- AdminBSB Theme -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/themes/theme-custom.css') }}">

    <style>
        .fp-page {
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


    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/custom.css') }}">

    @yield('style')

    <!-- Vue JS 3.x -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <!-- Vue Axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body class="theme-white">

    <section class="fp-page">
        @yield('content')
    </section>

    <!-- Jquery Core Js -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('plugins/node-waves/waves.js') }}"></script>

    <!-- Jquery Validation Plugin -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>


    <!-- Custom Js -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/pages/examples/sign-up.js') }}"></script>

    <!-- View's Js -->
    @yield('script')

    <script src="{{ asset('js/admin/admin.js') }}"></script>

</body>

</html>
