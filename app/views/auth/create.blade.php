<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>{{ Config::get('settings.title') }}</title>
        <link rel="shortcut icon" href="{{ Config::get('settings.icon') }}" />

        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" media="screen" type="text/css"/>
        <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    </head>
    <body style="background: url({{ asset('assets/images/auth_bg_pt.jpg') }}) no-repeat center fixed;    
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;">

        <div class="row">
            <div class="col-lg-12" style="margin-left: 25px;" id="my_back_button">
                <a href="http://www.perigest.pt">Voltar à Página Inicial</a>
            </div>
        </div>
        <div class="container">
            <div id="login-form">
                <h3><img src="{{ asset('assets/images/auth_logo.png') }}" width="85%"></h3>
                <fieldset>
                    {{ Form::open(['route' => 'auth.store', 'method' => 'POST']) }}
                    <input type="text" name="username" required placeholder="{{trans('users.username')}}">
                    <input type="password" name="password" required placeholder="{{trans('users.password')}}">
                    <p style="margin-top:5px;">
                        <input type="checkbox" name="remember" id="test1" />
                        <label for="test1">Manter sessão iniciada</label>
                    </p>
                    <input type="submit" value="Login">
                    <footer class="clearfix">
                        @foreach($errors->all() as $error)
                        <p><span class="info">!</span>{{ $error }}</p>
                        @endforeach
                    </footer>
                    {{ Form::close() }}
                </fieldset>
            </div> <!-- end login-form -->
        </div>
        <!--[if lt IE 9]>
        <script src="{{ asset('assets/plugins/respond.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/excanvas.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/plugins/jQuery-lib/1.10.2/jquery.min.js') }}"></script>
        <![endif]-->
        <!--[if gte IE 9]><!-->
        <script type="text/javascript" src="{{ asset('assets/plugins/jQuery-lib/2.0.3/jquery.min.js') }}"></script>
        <!--<![endif]-->
        <script type="text/javascript" src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    </body>

</html>

