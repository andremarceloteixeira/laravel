<?php
$notifications = Notification::Unreaded();
$notViewed = count(Notification::UnreadedJquery());
?>
<div class="navbar-tools">
    <!-- start: TOP NAVIGATION MENU -->
    <ul class="nav navbar-right">
        @if(Check::isClient())
         <li class="dropdown">
            <a id="notificationBadgePlace" data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                <i class="fa fa-flag"></i>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('home.lang', 'pt') }}">Portuguese</a></li>
                <li><a href="{{ route('home.lang', 'en') }}">English</a></li>
                <li><a href="{{ route('home.lang', 'es') }}">Espanhol</a></li>
            </ul>
        </li>
        @else
        <li class="dropdown">
            <a id="notificationBadgePlace" data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                <i class="fa fa-bullhorn"></i>
                @if($notViewed > 0)
                <span class="badge" id="notificationBadge">{{ $notViewed }}</span>
                @endif
            </a>
            <ul class="dropdown-menu notifications">
                <li>
                    <div class="drop-down-wrapper">
                        <ul id="notificationList">
                            @foreach($notifications as $n)
                            @if(!$n->jquery_viewed)
                            <li style="background:#efeded;">
                            @else
                            <li>
                            @endif
                                <a href="{{ route('notifications.show', $n->id) }}">
                                    <span class="label {{ $n->label }}"><i class="{{ $n->icon }}"></i></span>
                                    <span class="message"> {{ $n->name }}</span>
                                    <!-- <span class="time"> {{ Carbon::createFromFormat('Y-m-d H:i:s', $n->created_at)->diffForHumans() }}</span> -->
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        @if(Check::isEnableFeature('calendar'))
        <li>
            <a href="{{ route('calendar.index') }}"><i class="clip-calendar-3"></i></a>
        </li>
        @endif
        @endif
        <!-- start: USER DROPDOWN -->
        <li class="dropdown current-user">
            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                <img src="{{ asset(Auth::user()->photo) }}" class="img-rounded" width="30" height="30" alt="">
                <span class="username">{{ Auth::user()->name }}</span>
                <i class="clip-chevron-down"></i>
            </a>
            <ul class="dropdown-menu">
                @if(Check::isAdmin() || Check::isExpert() || Check::isClient(Auth::user()))
                <li>
                    <a href="{{ route('account.create') }}">
                        <i class="clip-user-2"></i>
                        &nbsp;{{ trans('navigation.profile') }}
                    </a>
                </li>
                <li class="divider"></li>
                @endif
                <li>
                    <a href="{{ route('auth.destroy') }}">
                        <i class="clip-exit"></i>
                        &nbsp;{{ trans('navigation.logout') }}
                    </a>
                </li>
            </ul>
        </li>
        <!-- end: USER DROPDOWN -->
    </ul>
    <!-- end: TOP NAVIGATION MENU -->
</div>