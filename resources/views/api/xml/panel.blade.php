@php
    echo '<?xml version="1.0" ?>'
@endphp
<recipies>
    @foreach($recipes as $recipe)
        <recipie>
            <username>{{ $recipe->user->name }}</username>
            <avatar>{{ $recipe->user->avatar }}</avatar>
            <city>@if($recipe->user->city_id){{$recipe->user->city->title}}@endif</city>
            <category>{{ $recipe->category->name }}</category>
            <ingredients>
            @foreach($recipe->ingredients as $ingredient)
                <item quant="{{ $ingredient->pivot->amount }}">{{ $ingredient->name }}</item>
            @endforeach
            </ingredients>
            <description>@foreach($recipe->cookings as $cooking){!! nl2br($cooking->body ).". "!!}@endforeach</description>
            <brief>{{ $recipe->name }}</brief>
            <time>{{ $recipe->cooking_time }}</time>
            <volume>Количество порций: {{ $recipe->cooking_volume }}</volume>
            <pictureURL>
                @if($recipe->images->count()){{ env('APP_IMAGE_URL'). $recipe->images[0]->original }}@endif
            </pictureURL>
        </recipie>
    @endforeach
</recipies>