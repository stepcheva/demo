@extends('layouts.master')

@section('content-header', '')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">

                    <div class="panel-heading">
                        <h5>
                            Пользователи
                        </h5>
                    </div>

                    <div class="panel-body">
                        <div class="box-body">
                            <form id="find" class="form-horizontal" method="GET"
                                  action="{{ route(Route::currentRouteName()) }}">
                                {{ csrf_field() }}
                                <label for="filter" class="col-md-3 control-label">Поиск по имени пользователя</label>
                                <div class="col-sm-4">
                                    <input id="filter" name="filter" class="form-control input-sm"
                                           placeholder="Имя пользователя" value="{{ $filter or '' }}">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-warning pull-right btn-block btn-sm">Найти
                                    </button>
                                </div>
                                <div class="col-sm-2">
                                    <a href="{{ route(Route::currentRouteName()) }}"
                                       class="btn btn-default pull-right btn-block btn-sm">Очистить поиск</a>
                                </div>
                            </form>
                        </div>

                        <div class="panel-body">
                            <!-- /.box-header -->
                            @if ($items->isEmpty())
                                <div class="box box-primary">
                                    <h5>Записей не найдено</h5>
                                </div>
                            @else
                                <div class="box box-primary">
                                    <div class="box-body table-responsive no-padding">
                                        <table id="recipes" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>#id</th>
                                                <th>Имя</th>
                                                <th>Аватар</th>
                                                <th>Провайдер</th>
                                                <th style="text-align: center">Активность</th>
                                                <th style="text-align: center">Блокировка</th>
                                                <th style="text-align: center">Баллы</th>
                                                <th style="text-align: center">Уровень</th>
                                                <th style="text-align: center">Рейтинг</th>
                                                <th style="text-align: center">Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @isset($items)
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td> {{ $item->id }} </td>
                                                        <td> {{ $item->name }} <br>
                                                            @isset($item->city_id){{ $item->city->title }} @endisset
                                                        </td>
                                                        <td>
                                                            <img class="img-circle img-bordered-sm" width=50
                                                                 src="{{ $item->avatar }}" alt="user image">
                                                        <td> {{ $item->social_driver  }} </td>
                                                        <td style="text-align: center">
                                                            @if($item->isActive())
                                                                <span class="badge bg-green">Активен</span>
                                                            @else
                                                                <span class="badge bg-red">Не активен</span>
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center">
                                                            <a href="{{ route($item->getTable().'.block', ['user' => $item]) }}"
                                                               class="cancel">
                                                                @if($item->isBlocked()) Разблокировать @else
                                                                    Заблокировать @endif
                                                            </a>
                                                            @if($item->isBlocked())<br><span
                                                                    class="badge bg-purple-active">Заблокирован</span>@endif
                                                        </td>
                                                        <td style="text-align: center">@if($item->is_active) {{ $item->points }} @endif</td>
                                                        <td style="text-align: center">
                                                            <span class="badge bg-yellow">{{ $item->level }}</span>
                                                            @if($item->level)
                                                                <img src="{{ asset('dist/img/levels/chef_cook_'.$item->level.'.png') }}">
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center"><a
                                                                    href="{{ route($item->getTable().'.details', [(str_singular($item->getTable()))  => $item]) }}"
                                                                    class="cancel">Детализация по баллам</a></td>
                                                        <td style="text-align: center">
                                                            <a href="{{ route($item->getTable().'.changeCity', [(str_singular($item->getTable()))  => $item]) }}"
                                                               class="cancel">Сбросить город</a>
                                                            <form id="delete" class="form-horizontal" method="POST"
                                                                  action="{{ route($item->getTable().'.destroy', [(str_singular($item->getTable())) => $item]) }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="submit"
                                                                        class="btn btn-danger pull-right btn-block btn-sm">
                                                                    Удалить
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                            </tbody>
                                        </table>
                                    </div>

                                    <ul class="pagination pagination-sm no-margin pull-right">
                                        <li>{{ $items->links() }}</li>
                                    </ul>

                                    <div>
                                        Показано: <span class="badge">{{ $items->lastItem() }}</span>
                                        из <span class="badge">{{ $items->total() }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
