<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Магнит | Форма семейного счастья </title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-blue.min.css') }}">
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    @stack("head")
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
@include('layouts.includes.header')

<!-- Left side column. contains the logo and sidebar -->
    @include('layouts.includes.aside')


    <div class="content-wrapper">

        @include('layouts.includes.flash')

        <section class="content-header">
            <h1>
                @yield('content-header')
                <small></small>
            </h1>
            <!--breadcrumbs
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>-->
        </section>


        <section class="content container-fluid">

            @yield('content')

        </section>

    </div>

    <div> @stack('hidden') </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Магнит | Форма семейного счастья
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2018 <a href="#">Магнит</a>.</strong> Все права защищены.
    </footer>

    <div class="control-sidebar-bg"></div>
</div>

@stack("script")
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

</body>
</html>