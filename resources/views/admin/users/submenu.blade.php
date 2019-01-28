@extends('layouts.master')

@section('content-header', 'Результаты акции')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li @if($month === '10')class="active"@endif><a href="{{route('results', [ $month => 10])}}" >Октябрь</a></li>
                            <li @if($month === '11')class="active"@endif><a href="{{route('results', [ $month => 11])}}" >Ноябрь</a></li>
                            <li @if($month === '12')class="active"@endif><a href="{{route('results', [ $month => 12])}}" >Декабрь</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane">

                                @include('admin.users.results', ['items' => $items])

                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
