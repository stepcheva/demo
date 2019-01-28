@extends('layouts.master')

@section('content-header', '')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">

                    <div class="panel-heading">
                        <h5>Последние
                            @lang("resources.items." . Route::current()->uri)
                        </h5>
                    </div>
                    <div class="panel-body">
                        <div class="box-body">
                            <form id="find" class="form-horizontal" method="GET"
                                  action="{{ route(Route::currentRouteName()) }}">
                                {{ csrf_field() }}
                                <label for="filter" class="col-md-3 control-label">Поиск по названию</label>
                                <div class="col-sm-4">
                                    <input id="filter" name="filter" class="form-control input-sm"
                                           placeholder="Название" value="{{ $filter or '' }}">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-warning pull-right btn-block btn-sm">Найти
                                    </button>
                                </div>
                                <div class="col-sm-2">
                                    <a href="{{ route(Route::currentRouteName()) }}" class="btn btn-default pull-right btn-block btn-sm">Очистить поиск</a>
                                </div>
                            </form>
                        </div>

                        <div class="box box-primary">
                            <!-- /.box-header -->
                            @if ($items->isEmpty())
                                <h5>Записей не найдено</h5></div>
                            @else
                                <div class="box-body table-responsive">
                                    <table id="recipes" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#id</th>
                                            <th>Название</th>
                                            <th>Категория</th>
                                            <th style="text-align: center">Автор</th>
                                            <th style="text-align: center">Аватар</th>
                                            @if($items[0]->getTable() === 'recipes')
                                                <th>От Шеф повара</th>
                                            @endif
                                            <th style="text-align: center">Дата создания</th>
                                            <th style="text-align: center">Опубликован</th>
                                            <th style="text-align: center">Лайков</th>
                                            <th style="text-align: center">Действия</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->name }} </td>
                                                    <td>
                                                        @if($item->getTable() === 'recipes')
                                                            {{ $item->category()->withTrashed()->first()->name }}
                                                        @else
                                                            {{ $item->chapter()->withTrashed()->first()->name }}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">{{ $item->user()->withTrashed()->first()->name }}
                                                        <br>
                                                        @isset($item->user()->withTrashed()->city_id)
                                                            {{ $item->user()->withTrashed()->first()->city->title }}
                                                        @endisset
                                                    </td>
                                                    <td style="text-align: center">
                                                        <img class="img-circle img-bordered-sm" width=50
                                                             src="{{ $item->user->avatar }}" alt="user image">
                                                        <p>{{ $item->user->social_driver }}</p>
                                                    </td>
                                                    @if($item->getTable() === 'recipes')
                                                        <td style="text-align: center">
                                                            @if(! $item->from_shief)
                                                                <a class="btn btn-success btn-sm"
                                                                   href="{{ route($item->getTable().'.chief.cooker', [(str_singular($item->getTable())) => $item]) }}">Установить</a>
                                                            @else
                                                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td style="text-align: center"> {{ $item->created_at }} </td>
                                                    <td style="text-align: center">
                                                        @if($item->is_published)
                                                            <span class="badge bg-yellow">
                                                        <i class="fa fa-check-circle-o"
                                                           aria-hidden="true"></i></span>@endif</td>
                                                    <td style="text-align: center">
                                                        @if($item->is_published){{ $item->likes }}@endif</td>
                                                    <td style="text-align: center">
                                                        <form method="POST"
                                                              action="{{ route ($item->getTable().'.destroy', [(str_singular($item->getTable())) => $item]) }}">
                                                            <a class="btn btn-primary btn-sm"
                                                               href="{{ route($item->getTable().'.show', [(str_singular($item->getTable())) => $item]) }}">Показать</a>
                                                            <input type="hidden" name="_method" value="delete"/>
                                                            <input type="hidden" name="_token"
                                                                   value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-danger btn-sm">Удалить
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div></div>

                                <ul class="pagination pagination-sm no-margin pull-right">
                                    <li>{{ $items->links() }}</li>
                                </ul>

                                <div>
                                    Показано: <span class="badge">{{ $items->lastItem() }}</span>
                                    из <span class="badge">{{ $items->total() }}</span>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



