<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->

    <title>Car-Ticket</title>

    <!-- Scripts -->
   <script src="js/bootstrap.js" type="text/javascript"></script>
   <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
   <script src="{{asset('adminLte/dist/js/adminlte.js')}}" type="text/javascript"></script>
   <script src="{{asset('assets/js/jquery.gritter.min.js')}}" type="text/javascript"></script>
   <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
  
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{asset('adminLte/plugins/iCheck/square/blue.css') }}" rel="stylesheet">
    <link href="{{asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{asset('assets/template/admin/css/AdminLTE.min.css') }}" rel="stylesheet">
    <link href="{{asset('assets/template/admin/css/skins/skin-blue.min.css') }}" rel="stylesheet">
    <link href="{{asset('adminLte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet">
</head>
<body class="hold-transition login-page content">
    <div class="container">
        <div class="login-box">
            <div class="login-logo">
                <a><b>Car</b>-Ticket</a>
            </div>
            <div class="login-box-body">
            @if(session('success'))
                <div class="col-md-12 offset-md-12">
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="col-md-12 offset-md-12">
                    <div class="alert alert-danger">
                        {{session('error')}}
                    </div>
                </div>
            @endif
                <p class="login-box-msg">Connectez vous</p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control @error('pseudo') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="E-mail" required autocomplete="email" autofocus>
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                 @error('pseudo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group has-feedback">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Mot de passe">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                           <div class="form-group row mb-0">
                                <div class="col-md-12 offset-md-12">
                                    <button type="submit" class="btn btn-block btn-flat btn-warning">
                                        Connexion
                                    </button><br/>
                                </div>
                               <p class="text-center">
                                   @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{route('password.request')}}">
                                            Mot de passe oublier ?
                                        </a>
                                   @endif
                                    </p>
                            </div>
                        </form>
            </div>
        </div>
    </div>
<script type="text/javascript">
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>

