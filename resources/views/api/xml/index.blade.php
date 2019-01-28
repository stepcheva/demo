@extends('layouts.master')

@section('content-header', '')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">

                    @if(! empty($items))

                        <div class="panel-heading">
                            @if(empty($items))
                                <h5> Нет ни одной записи в БД </h5>
                            @else
                                <h5>Управление Touch-панелью</h5>
                            @endif
                        </div>

                        <div class="panel-body">
                            <div class="box box-primary">
                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <table id="recipes" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#id</th>
                                            <th>Название</th>
                                            <th>Категория</th>
                                            <th style="text-align: center">Автор</th>
                                            <th style="text-align: center">Аватар</th>
                                            <th style="text-align: center">Дата создания</th>
                                            <th style="text-align: center">Лайков</th>
                                            <th style="text-align: center">На панели</th>
                                            <th style="text-align: center">Действия</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                        </thead>
                                        <tbody>

                                        @if (isset($items))
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

                                                    <td style="text-align: center"> {{ $item->created_at }} </td>
                                                    <td style="text-align: center">
                                                        @if($item->is_published){{ $item->likes }}@endif</td>
                                                    <td style="text-align: center">
                                                        @if($item->touchs()->count())
                                                            <span class="badge bg-yellow">
                                                            <i class="fa fa-check-circle-o"
                                                               aria-hidden="true"></i></span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">
                                                        <form method="POST"
                                                              action="{{ route ('xml.destroy', ['item' => $item]) }}">
                                                            @if(! $item->touchs()->count())
                                                                <a class="btn btn-primary btn-sm"
                                                                   href="{{ route ('xml.add', ['item' => $item]) }}">Добавить</a>
                                                            @else
                                                                <input type="hidden" name="_method" value="delete"/>
                                                                <input type="hidden" name="_token"
                                                                       value="{{ csrf_token() }}">
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    Удалить
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <h2> Нет данных в базе</h2>
                                        @endif
                                        </tbody>

                                    </table>

                                </div>

                            </div>
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li>{{ $items->links() }}</li>
                            </ul>

                            <div>
                                Показано: <span class="badge">{{ $items->lastItem() }}</span>
                                из <span class="badge">{{ $items->total() }}</span>
                            </div>
                        </div>
                    @else
                        <h5> Нет ни одной записи в БД </h5>
                    @endif
                </div>
            </div>
        </div>
@endsection




