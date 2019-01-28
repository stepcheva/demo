@extends('layouts.master')

@section('content-header', 'Пользователи / Детальная страница пользователя')
@section('content')
    <div>
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile ">
                        <img class="profile-user-img img-responsive img-circle" src="{{ $user->avatar }}"
                             alt="User profile picture">
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">
                            @if(isset($user->city_id))
                                {{ $user->city->title }}
                            @else
                                Город не указан
                            @endisset
                        </p>

                        @if($user->level)
                            <div class="box box-danger text-center">
                                <img src="{{ asset('dist/img/levels/chef_cook_'.$user->level.'@2x.png' ) }}">
                            </div>
                        @endif
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Создано рецептов</b> <a class="pull-right">{{ $user->recipes->count() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Создано лайфхаков</b> <a class="pull-right">{{ $user->lifehacks->count() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Баллов</b> <a class="pull-right">{{ $user->points }}</a>
                                </li>
                            </ul>
                            @if($user->isActive())
                                <span class="badge bg-green text-center">Активен</span>
                            @else
                                <span class="badge bg-red text-center">Не активен</span>
                            @endif
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-thumbs-o-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">За рецепты</span>
                                    <span class="info-box-number">{{ $recipesLikes }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-maroon"><i class="fa fa-thumbs-o-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">За лайфхаки</span>
                                    <span class="info-box-number">{{ $lifehacksLikes }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-thumbs-o-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Комментариев</span>
                                    <span class="info-box-number">{{ $comments }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Чеков</span>
                                    <span class="info-box-number">{{ $user->qrs->count() }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>

                @php $i = 0 @endphp
                @if($user->qrs->count())
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Информация по чекам</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Дата сканирования</th>
                                    <th>Номер чека</th>
                                    <th>Начисленные баллы</th>
                                </tr>
                                @foreach($user->qrs as $qr)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $qr->created_at }}</td>
                                        <td>{{ $qr->key }}</td>
                                        <td>{{ $qr->points  }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td colspan="4">
                                            <span class="badge bg-blue">Всего по чекам: {{ $qrs }} баллов</span>
                                        </td>
                                    </tr>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
