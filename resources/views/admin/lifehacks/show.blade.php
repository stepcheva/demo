@extends('layouts.master')

@section('content-header', 'Лайфхаки / Детальная страница лайфхака')

@section('content')
    <div>
        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{ $user->avatar }}" alt="user image">
                        <span class="username">
                                <a href="#">{{ $user->name }}</a>
                            </span>
                        <span class="description">{{ $city->title }}</span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row margin-bottom">
                        <div class="box-body">
                            <div class="col-md-6">
                                <dl class="dl-horizontal">
                                    <dt>Лайфхак</dt>
                                    <dd>{{ $lifehack->name }}</dd>
                                    <dt>Категория</dt>
                                    <dd>{{ $chapter->name}}</dd>
                                </dl>
                            </div>
                            <div class="row">
                                <div class="box-body">
                                    @foreach($lifehack->images as $key => $image)
                                        <img src="{{ env('APP_IMAGE_URL') . $image->medium }}" width="180" alt="">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="box-body">
                        @foreach($lifehack->instructions as $instruction)
                            <div class="attachment-block clearfix">
                                @isset($instruction->image_small)
                                    <img class="attachment-img"
                                         src="{{env('APP_IMAGE_URL').$instruction->image_small }}" alt="Фото шага">
                                @endisset
                                <div class="attachment-pushed">
                                    <h4 class="attachment-heading">Шаг {{ $instruction->step }}
                                        . {{ $instruction->name }}</h4>
                                    <div class="attachment-text">
                                        {!! nl2br($instruction->body) !!}
                                    </div>
                                    <!-- /.attachment-text -->
                                </div>
                                <!-- /.attachment-pushed -->
                            </div>
                        @endforeach
                    </div>
                    <div class="box-header">
                        <div class="form-group margin-bottom-none">
                            <form id="publish" class="form-horizontal" method="POST"
                                  action="{{ route('lifehacks.update', ['lifehack' => $lifehack]) }}">
                                {{ csrf_field() }}
                                <div class="col-sm-3">
                                    {{ method_field('PUT') }}
                                    <button type="submit" class="btn btn-success pull-right btn-block btn-sm">
                                        Опубликовать
                                    </button>
                                </div>
                            </form>

                            <form id="delete" class="form-horizontal" method="POST"
                                  action="{{ route('lifehacks.destroy', ['id' => $lifehack->id]) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-danger pull-right btn-block btn-sm">Удалить
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
