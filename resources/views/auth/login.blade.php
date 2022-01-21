





<!DOCTYPE html>
<html>


<!-- Mirrored from webapplayers.com/inspinia_admin-v2.9.3/login_two_columns.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Sep 2020 06:10:52 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Binni | Login</title>

    <link href="{{ asset('assets/backend/layouts/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{ asset('assets/backend/layouts/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/backend/layouts/css/style.css')}}" rel="stylesheet">
    <style>
        body{
            display: flex;
            align-items: center;
        }
     </style>
</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">Welcome to BINNI</h2>

                <p>
                    Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
                </p>

                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>

                <p>
                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                </p>

                <p>
                    <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form"  method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input id="email" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <small>Forgot password?</small>
                            </a>
                        @endif                        
                    </form>
                    <p class="m-t">
                        <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Venture Solution Limited
            </div>
            <div class="col-md-6 text-right">
               <small>© 2020-{{ date('Y') }}</small>
            </div>
        </div>
    </div>

</body>
</html>
