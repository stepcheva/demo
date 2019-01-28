<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">МЕНЮ</li>

            <li class="{{ in_array(Route::currentRouteName(), ['recipes.index', 'recipes.create', 'recipes.show']) ? ' active': '' }}">
                <a href="{{ route('recipes.index') }}"><i class="fa fa-link"></i></i><span>Рецепты</span></a></li>
            <li class="{{ in_array(Route::currentRouteName(), ['lifehacks.index', 'lifehacks.create', 'lifehacks.show']) ? ' active': '' }}">
                <a href="{{ route('lifehacks.index') }}"><i class="fa fa-link"></i> <span>Лайфхаки</span></a></li>
            <li class="{{ in_array(Route::currentRouteName(), ['users.index', 'users.create', 'users.show']) ? ' active': '' }}">
                <a href="{{ route('users.index') }}"><i class="fa fa-link"></i><span>Пользователи</span></a></li>
            <li class="{{ in_array(Route::currentRouteName(), ['comments.index']) ? ' active': '' }}">
                <a href="{{ route('comments.index') }}"><i class="fa fa-link"></i><span>Комментарии</span></a></li>

            <li class="{{ in_array(Route::currentRouteName(), ['contacts.index']) ? 'active treeview': 'treeview' }} ">
                <a href="#"><i class="fa fa-link"></i><span>Обращения</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                </a>
                <ul class="treeview-menu" style="display: block;">
                    <li class="{{ isset($param) && $param === 'new' ? 'active': '' }}">
                        <a href="{{ route('contacts.index', [ 'param' => 'new']) }}"><i
                                    class="fa fa-circle-o text-red"></i>Новые</a></li>
                    <li class="{{ isset($param) && $param === 'answered' ? 'active': '' }}">
                        <a href="{{ route('contacts.index', [ 'param' => 'answered']) }}"><i
                                    class="fa fa-circle-o text-green"></i>Просмотренные</a></li>
                </ul>
            </li>

            <li><a href="#"><i class="fa fa-link"></i> <span>Статистика</span></a></li>

            <li class="{{ in_array(Route::currentRouteName(), ['contents.index']) ? ' active': '' }}">
                <a href="{{ route('contents.index') }}"><i class="fa fa-link"></i><span>Загрузить магазины</span></a>
            </li>
            <li class="{{ in_array(Route::currentRouteName(), ['products.index']) ? 'active treeview': 'treeview' }} ">
                <a href="#"><i class="fa fa-link"></i><span>Товары спонсоров</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                </a>
                <ul class="treeview-menu" style="display: block;">
                    <li class="{{ isset($param) && $param === 'products' ? 'active': '' }}">
                        <a href="{{ route('products.index', [ 'param' => 'products']) }}"><i
                                    class="fa fa-circle-o text-red"></i>Товары</a></li>
                    <li class="{{ isset($param) && $param === 'product_groups' ? 'active': '' }}">
                        <a href="{{ route('products.index', [ 'param' => 'product_groups']) }}"><i
                                    class="fa fa-circle-o text-green"></i>Категории</a></li>
                </ul>
            </li>

            <li class="{{ in_array(Route::currentRouteName(), ['xml.index']) ? ' active': '' }}">
                <a href="{{ route('xml.index') }}"><i class="fa fa-link"></i></i><span>Touch-панель</span></a></li>

            <li class="{{ in_array(Route::currentRouteName(), ['results']) ? ' active': '' }}">
                @php if(!isset($month)) $month = 10  @endphp
                <a href="{{ route('results', ['month' => $month]) }}"><i class="fa fa-link"></i><span>Результаты акции</span></a></li>
        </ul>
    </section>
</aside>
