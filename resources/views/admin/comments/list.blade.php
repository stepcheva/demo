@extends('layouts.master')

@section('content-header', '')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    @if(empty($items))
                        <h5> Нет ни одной записи в БД </h5>
                    @else
                        <h5>
                            Жалобы на комментарии
                        </h5>
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
                                    <th>Автор</th>
                                    <th style="text-align: center">Аватар</th>
                                    <th style="text-align: center">Комментарий</th>
                                    <th style="text-align: center">Активность</th>
                                    <th style="text-align: center">Блокировка</th>
                                    <th style="text-align: center">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($items)
                                    @foreach($items as $item)
                                        <tr>
                                            <td> {{ $item->id }} comment</td>
                                            <td>
                                                <strong>{{ $item->user()->withTrashed()->first()->name }}</strong><br>
                                                @isset($item->user()->withTrashed()->first()->city_id){{ $item->user()->withTrashed()->first()->city->title }} @endisset
                                                <br>
                                            </td>
                                            <td style="text-align: center">
                                                <img class="img-circle img-bordered-sm" width=50
                                                     src="{{ $item->user()->withTrashed()->first()->avatar }}" alt="user image">
                                                <p>{{ $item->user()->withTrashed()->first()->social_driver }}</p>
                                            </td>
                                            <td style="text-align: center">{{ $item->body }}</td>
                                            <td style="text-align: center">
                                                @if($item->user()->withTrashed()->first()->isActive())
                                                    <span class="badge bg-green">Активен</span>
                                                @else
                                                    <span class="badge bg-red">Не активен</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                <a href="{{ route('users.block', ['user' => $item->user]) }}"
                                                   class="cancel">
                                                    @if($item->user()->withTrashed()->first()->isBlocked()) Разблокировать @else
                                                        Заблокировать @endif
                                                </a>
                                                @if($item->user()->withTrashed()->first()->isBlocked())<br><span class="badge bg-purple-active">Заблокирован</span>@endif
                                            </td>
                                            <td style="text-align: center">
                                                <form id="delete" class="form-horizontal" method="POST"
                                                      action="{{ route($item->getTable().'.destroy', [(str_singular($item->getTable())) => $item]) }}">
                                                    <a class="btn btn-primary btn-sm"
                                                       href="{{ route('comments.approve', [(str_singular($item->getTable())) => $item]) }}">Одобрить</a>
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm">Удалить
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
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
            </div>
        </div>
    </div>
@endsection
