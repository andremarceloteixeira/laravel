<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        @if(isset($topFixed))
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        @endif
        <style type="text/css">
            body {
                font-family: Tahoma, Geneva, sans-serif !important;
                size: A4 portrait;
                margin: 0;
            }

            .border-top{
                border-top: 15.5px solid rgba(40, 57, 115, 1);
            }

            .border-bottom {
                border-bottom: 15.5px solid rgba(40, 57, 115, 1);

            }

            .subheader {
                border-top: 1.5px solid rgba(40, 57, 115, 1);
                border-bottom: 1.5px solid rgba(40, 57, 115, 1);
                margin:0;
                padding:0;
            }

            .subheader h3 {
                font-size: 12pt;
                padding: 13px 5px 13px 5px;
                margin:0 !important;
            }

            .logo {
                padding-top: 15px;
                padding-bottom: 15px;
            }

            .title {
                color: rgba(40, 57, 115, 1);
                font-weight: 600;
            }

            .text {
                color: #000;
            }

            .content {
                padding-left:18px;
                padding-right:18px;
            }

            .welcome_regards {
                font-size: 9pt;
                padding-top: 50px;
                padding-bottom: 35px;
            }

            .text-center {
                text-align: center;
            }

            .text-left {
                text-align: left;
            }

            .text-right {
                text-align: right;
            }

            .field {
                font-size: 9pt;
                padding-top: 2px;
                padding-bottom: 2px;
            }

            .footer {
                font-size: 8.5pt;
                margin-top:15px;
                margin-bottom: 25px;
                padding-top: 15px;
                padding-bottom: 8px;
                border-top: 1.5px solid rgba(40, 57, 115, 1);
            }
            <?php if(isset($topFixed)) { ?>
            .navbar-inverse .navbar-collapse, .navbar-inverse .navbar-form {
                border-color: #2d4259;
            }
            
            .navbar-inverse .navbar-toggle:hover, .navbar-inverse .navbar-toggle:focus {
                background-color: #2d4259;
                border-color: #273849;
            }
            <?php } ?>
        </style>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800,300' rel='stylesheet' type='text/css'>
        <title>@yield('title')</title>
    </head>    

    <body>
        @if(isset($topFixed))
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background:#364f6a;">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    {{ HTML::image('assets/images/logo_report.png', null,['style' => 'margin-top: 3px;']) }}
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <form class="navbar-form navbar-right" role="form">
                        <a class="btn btn-default" href="{{ $back }}"><i class="fa fa-arrow-left"></i> Voltar atrás</a>
                        <a class="btn btn-success" href="{{ $edit }}"><i class="fa fa-edit"></i> Editar o Processo</a>
                        <a class="btn btn-primary" href="{{ $topFixed }}" data-toggle="tooltip" data-placement="bottom" title="{{ trans('actions.download_tooltip') }}"><i class="fa fa-download"></i> {{ trans('actions.download') }}</a>
                    </form>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <table style="width: 700px; margin-top: 85px;" align='center' class="container borders" cellspacing='0' cellpadding='0'>
        @else
        <table style="width: 700px;" align='center' class="container borders" cellspacing='0' cellpadding='0'>
        @endif
                <tr>
                    <td colspan="2" class="border-top text-center">
                        <img src="{{ asset('assets/images/logo2.png') }}" width="450" height="70" alt="Perigest Logo"> 
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        @yield('main')
                    </td>
                </tr>
                <tr>
                    <td class="subheader text-left">
                        <h3><span class="title">@yield('title')</span></h3> 
                    </td>
                    <td class="subheader text-right">
                        <h3><span class="title">{{ trans('processes.certificate') }} :</span> <span class="text">@yield('certificate')</span></h3>
                    </td>
                </tr>
                @yield('content')
                <tr>
                    <td class="footer border-bottom text-left">
                        <p><span style="color:rgba(40, 57, 115, 1);"><b>Porto - Sede</b></span></p>
                        <p>Rua das Coletividades, nº 54 6º Salas 6.2, 6.3, 6.4</p>
                        <p>4430-625 Vila Nova de Gaia - Portugal</p>
                        <p>Tel: +351 224 834 092 / Fax: +351 224 834 093</p>
                        <p>E-mail: geral@perigest.pt / www.perigest.pt</p>
                    </td>
                    <td class="footer border-bottom text-right">
                        <p><span style="color:rgba(40, 57, 115, 1);"><b>Lisboa</b></span>
                        <p>E.N. 10, KM 127 - Sala</p>
                        <p>2115-124 Alverca do Ribatejo</p>
                        <p>Tel: +351 219 582 536 / Fax: +351 224 834 093</p>
                        <p>E-mail: lisboa@perigest.pt / www.perigest.pt</p>
                    </td>
                </tr>
            </table>
            @if(isset($topFixed))
            <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
            <script>
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })
            </script>
            @endif
    </body>
</html>