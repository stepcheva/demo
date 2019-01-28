<div class="panel-body">
    <div class="box box-primary">
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            @php $i = 0 @endphp
            <table id="recipes" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>#id</th>
                    <th>Имя</th>
                    <th>Аватар</th>
                    <th>Провайдер</th>
                    <th style="text-align: center">Блокировка</th>
                    <th style="text-align: center">Баллы</th>
                    <th style="text-align: center">Уровень</th>
                    <th style="text-align: center">Рейтинг</th>
                    <th style="text-align: center">Информация</th>
                </tr>
                </thead>
                <tbody>
                @isset($items)
                    @foreach($items as $item)
                        <tr>
                            <td> {{ $item->user->id }} </td>
                            <td> {{ $item->user->name }} <br>
                                @isset($item->user->city_id){{ $item->user->city->title }} @endisset</td>
                            <td>
                                <img class="img-circle img-bordered-sm" width=50 src="{{ $item->user->avatar }}"
                                     alt="user image">
                            <td> {{ $item->user->social_driver }}<br>{{ $item->user->social_id }}</td>
                            <td style="text-align: center">
                                @if($item->user->isActive())
                                    <span class="badge bg-green">Активен</span>
                                @else
                                    <span class="badge bg-red">Не активен</span>
                                @endif
                            </td>
                            <td style="text-align: center">{{ $item->points }}</td>
                            <td style="text-align: center">
                                @if ($item->points >= 500)
                                    <span class="badge bg-yellow">{{ 4 }}</span>
                                    <img src="{{ asset('dist/img/levels/chef_cook_4.png') }}">
                                @elseif($item->points >= 250)
                                    <span class="badge bg-yellow">{{ 3 }}</span>
                                    <img src="{{ asset('dist/img/levels/chef_cook_3.png') }}">
                                @elseif($item->points >= 150)
                                    <span class="badge bg-yellow">{{ 2 }}</span>
                                    <img src="{{ asset('dist/img/levels/chef_cook_2.png') }}">
                                @elseif($item->points >= 50)
                                    <span class="badge bg-yellow">{{ 1 }}</span>
                                    <img src="{{ asset('dist/img/levels/chef_cook_1.png') }}">
                                @elseif($item->points < 50)
                                    <span class="badge bg-yellow">{{ 0 }}</span>
                                @endif
                            </td>
                            <td style="text-align: center">{{ ++$i }}</td>
                            <td>
                                <h6>Рецептов: {{ $item->recipes }}</h6>
                                <h6>Лайков за рецепты: {{ $item->recipes_likes }}</h6>
                                <h6>Лайхаков: {{ $item->lifehacks }}</h6>
                                <h6>Лайков за лайфхаки: {{ $item->lifehacks_likes }}</h6>
                                <h6>Комментариев: {{ $item->comments }}</h6>
                                <h6>Чеков всего: {{ $item->qrs_count }}</h6>
                                <h6>Баллов по чекам: {{ $item->qrs_points }}</h6>
                            </td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
