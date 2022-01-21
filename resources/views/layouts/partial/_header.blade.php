
<!DOCTYPE html>
<html>


<!-- Mirrored from webapplayers.com/inspinia_admin-v2.9.3/dashboard_4_1.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Sep 2020 06:09:06 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BINNI | @yield('title')</title>

    <link href="{{ asset('assets/backend/layouts/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/font-awesome/css/font-awesome.css')}}" rel="stylesheet">


    <link href="{{ asset('assets/backend/layouts/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/style.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/backend/layouts/css/toastr.min.css')}}">

    <link href="{{ asset('assets/backend/layouts/css/custom-style.css')}}" rel="stylesheet">

    <style>
        button.swal2-cancel.btn.btn-danger {
            margin-right: 12px;
            padding: 5px 12px;
            border: none;
            border-radius: 0;
        }
        button.swal2-confirm.btn.btn-success {
            padding: 5px 12px;
            background: #1cc09f;
            border: none;
            border-radius: 0;
        }
        .help-block {
            font-size: 80%;
            font-weight: bold;
            color: red;
        }
    </style>

    @stack('css')

</head>