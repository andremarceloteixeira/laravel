<?php 
    $prefix = explode('.', Route::currentRouteName())[0];
?>
<!-- start: SIDEBAR -->
<div class="main-navigation navbar-collapse collapse">
    <!-- start: MAIN MENU TOGGLER BUTTON -->
    <div class="navigation-toggler">
        <i class="clip-chevron-left"></i>
        <i class="clip-chevron-right"></i>
    </div>
    <!-- end: MAIN MENU TOGGLER BUTTON -->
    <!-- start: MAIN NAVIGATION MENU -->
    <ul class="main-navigation-menu">
        @foreach(Config::get('settings.sidebar') as $k => $v)
            @if(in_array(Auth::user()->role_id, $v['roles']))
            <?php
                if($prefix == explode('.', $k)[0]) {
                    $active = "active";
                } else {
                    $active = "";
                }

            ?>
            <li class="{{ @$active }}">
                <a href="{{route($v['route']) }}"><i class="{{ $v['ico'] }}"></i>
                    <span class="title"> {{ trans('navigation.'.$k) }} </span><span class="selected"></span>
                </a>
            </li>
            @endif
        @endforeach

    </ul>
    <!-- end: MAIN NAVIGATION MENU -->
</div>
<!-- end: SIDEBAR -->