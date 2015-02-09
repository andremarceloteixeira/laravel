<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <title>{{ Config::get('settings.title') }}</title>
        <!-- start: META -->
        <meta charset="utf-8" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="Web Application" name="description" />
        <meta content="Perigest" name="author" />
        <!-- end: META -->
        <!-- start: MAIN CSS -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fonts/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main-responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/skins/all.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ Config::get('settings.template') }}" type="text/css" id="skin_color">
        <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" type="text/css'" media="print"/>
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/css/datepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" type="text/css"/>
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" type="text/css"/>
        <link rel="stylesheet" href="{{ asset('assets/plugins/DataTables/media/css/dataTables.bootstrap.css') }}"/>
        <link rel="stylesheet" href="{{ asset('assets/plugins/ladda-bootstrap/dist/ladda-themeless.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/gritter/css/jquery.gritter.css') }}">
        <!--[if IE 7]>
        <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome-ie7.min.css') }}">
        <![endif]-->
        <!-- end: MAIN CSS -->
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        @yield('css-required')
        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
        <link rel="shortcut icon" href="{{ Config::get('settings.icon') }}" />
    </head>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body>
        <!-- start: HEADER -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <!-- start: TOP NAVIGATION CONTAINER -->
            <div class="container">
                <div class="navbar-header">
                    <!-- start: RESPONSIVE MENU TOGGLER -->
                    <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                        <span class="clip-list-2"></span>
                    </button>
                    <!-- end: RESPONSIVE MENU TOGGLER -->
                    <!-- start: LOGO -->
                    <a class="navbar-brand" href="{{ route('home.index') }}">
                        {{ HTML::image( Config::get('settings.logo_img'), null, ['class' => 'logo_img']) }} 
                        <span class="logo_title">{{ Config::get('settings.logo_title') }}</span>
                    </a>
                    <!-- end: LOGO -->
                </div>
                @include('tools')
            </div>
            <!-- end: TOP NAVIGATION CONTAINER -->
        </div>
        <!-- end: HEADER -->
        <!-- start: MAIN CONTAINER -->
        <div class="main-container">
            <div class="navbar-content">
                @include('sidebar')
            </div>
            <!-- start: PAGE -->
            <div class="main-content">
               <div class="container">
                    <!-- start: PAGE HEADER -->
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- start: PAGE TITLE & BREADCRUMB -->
                            <ol class="breadcrumb">
                                <li><i class="clip-home-3"></i> <a href="{{ route('home.index') }}">{{ trans('navigation.home') }}</a></li>
                                @yield('breadcrumb')   
                                @if(!Check::isClient())
                                <li class="search-box">
                                    <form class="sidebar-search">
                                        <div class="form-group">
                                            <input id="globalSearch" type="text" placeholder="{{ trans('actions.search') }}">
                                            <button class="submit">
                                                <i class="clip-search-3"></i>
                                            </button>
                                        </div>
                                    </form>
                                </li>
                                @endif
                            </ol>
                            <div class="page-header">
                                <h1>@yield('title')</h1>
                            </div>
                            <!-- end: PAGE TITLE & BREADCRUMB -->
                        </div>
                    </div>
                    @if(Session::has('notification'))
                    <div class="alert alert-block alert-success fade in">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <h4 class="alert-heading"><i class="fa fa-check-circle"></i> {{ trans('actions.success') }}!</h4>
                        <p> {{ Session::get('notification') }} </p>     
                    </div>
                    @endif
                    @if(Session::has('credentials'))
                    <div class="alert alert-info">
                        <button data-dismiss="alert" class="close">×</button>
                        {{ Session::get('credentials') }}
                    </div>
                    @endif
                    <!-- end: PAGE HEADER -->
                    <!-- start: PAGE CONTENT -->
                    @yield('main')
                    <!-- end: PAGE CONTENT -->
                </div>
            </div>
            <!-- end: PAGE -->
        </div>
        <!-- end: MAIN CONTAINER -->
        <!-- start: FOOTER -->
        <div class="footer clearfix">
            <div class="footer-inner">
                {{ Config::get('settings.copyright') }}
            </div>
            <div class="footer-items">
                <span class="go-top"><i class="clip-chevron-up"></i></span>
            </div>
        </div>
        <!-- end: FOOTER -->
        <div id="event-management" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">Event Management</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-light-grey">
                            Close
                        </button>
                        <button type="button" class="btn btn-danger remove-event no-display">
                            <i class='fa fa-trash-o'></i> Delete Event
                        </button>
                        <button type='submit' class='btn btn-success save-event'>
                            <i class='fa fa-check'></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- start: GLOBAL MODAL -->
        <div id="form_modal" class="modal fade" tabindex="-1" data-width="560" style="display: none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="form_modal_title" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="form_modal_left"  class="col-md-4">

                    </div>
                    <div id="form_modal_right" class="col-md-8">

                    </div>
                </div>
            </div>
            <div id="form_modal_buttons" class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-blue">
                    {{ trans('actions.close') }}
                </button>
            </div>
        </div>
        <!-- end: GLOBAL MODAL --> 

        <!-- start: CONFIRM MODAL-->  
        <div id="confirm_modal" class="modal fade" tabindex="-1" data-width="560" style="display: none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="confirm_modal_title" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p id="confirm_modal_body"></p>
            </div>
            <div class="modal-footer">
                <button aria-hidden="true" data-dismiss="modal" class="btn btn-default">{{ trans('actions.close') }}</button>
                <button class="btn btn-blue btn-md ladda-button" data-style="expand-right"  id="confirm_modal_okay">
                    <span class="ladda-label"> {{ trans('actions.confirm') }} </span>
                    <i class="fa fa-arrow-circle-right"></i>
                    <span class="ladda-spinner"></span>
                    <span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
                </button>
            </div>
        </div>
        <!-- end: CONFIRM MODAL -->  

        <!-- start: MAIN JAVASCRIPTS -->
        <!--[if lt IE 9]>
        <script src="{{ asset('assets/plugins/respond.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/excanvas.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/jQuery-lib/1.10.2/jquery.min.js') }}"></script>
        <![endif]-->
        <!--[if gte IE 9]><!-->
        <script type="application/javascript" src="{{ asset('assets/plugins/jQuery-lib/2.0.3/jquery.min.js') }}"></script>
        <!--<![endif]-->
        <script type="application/javascript" src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/blockUI/jquery.blockUI.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/iCheck/jquery.icheck.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/less/less-1.5.0.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/DataTables/media/js/jquery.dataTables.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/ladda-bootstrap/dist/spin.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/ladda-bootstrap/dist/ladda.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/gritter/js/jquery.gritter.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/plugins/autosize/jquery.autosize.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/js/main.js') }}"></script>
        <script type="application/javascript" src="{{ asset('assets/js/settings.js') }}"></script>
        <!-- start: GLOBAL FUNCTIONS -->
        <script>
        jQuery(document).ready(function() {
            Main.init();
            Select.init('.search-select');
            DatePicker.init('.date-picker');
            
            setInterval(function(){ Notification.update('<?php echo route('notifications.all'); ?>'); }, 20000);
            
            $('#notificationBadgePlace').on('click', function() {
                $.post('<?php echo route('notifications.jqueryViewed') ?>', {}, function(data) {
                    if(data['status']==="success") {
                        $('#notificationBadge').remove();
                    }
                });
            });
            
            function custom_source(request, response) {
                var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(<?php echo Helper::searchAutoComplete() ?>, function(value) {
                    return matcher.test(value.searchable);
                }));
            }
            
            $("#globalSearch").catcomplete({
                source: custom_source,
                select: function(event, ui) {
                    window.location = ui.item.url;
                }
            });
        });
        </script>
        <!-- end: GLOBAL FUNCTIONS -->
        <!-- end: MAIN JAVASCRIPTS -->
        <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
        @yield('js-required')
        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    </body>
    <!-- end: BODY -->
</html>