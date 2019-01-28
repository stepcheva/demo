<header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">MM</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Магнит</b> | Админ</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

            <!-- Messages: style can be found in dropdown.less-->
            @include('layouts.includes.messages')

            <!-- Notifications Menu -->
            @include('layouts.includes.notifications')

            <!-- Tasks Menu -->
            @include('layouts.includes.tasksmenu')

            <!-- User Account Menu -->
            @include('layouts.includes.users')
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>