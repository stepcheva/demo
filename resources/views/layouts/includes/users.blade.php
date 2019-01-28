<li class="dropdown user user-menu">
    <!-- Menu Toggle Button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <!-- The user image in the navbar-->
        <img src="{{ asset('pages/img/form_logo_small.png') }}" class="user-image" alt="User Image">
        <!-- hidden-xs hides the username on small devices so only the image appears. -->
        <span class="hidden-xs">{{ Auth::user()->name }}</span>
    </a>
    <ul class="dropdown-menu">
        <!-- The user image in the menu -->
        <li class="user-header">
            <img src="{{ asset('dist/img/Icon-60@3x.png') }}" class="img-circle" alt="User Image">

            <p> {{ Auth::user()->name }}
                <small>администратор</small>
            </p>
        </li>

        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat">Профиль</a>
            </div>
            <div class="pull-right">
                <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">Выход
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

            </div>
        </li>
    </ul>
</li>