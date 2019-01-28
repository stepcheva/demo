<?php

Route::redirect('/', 'api/v1/feed', 301);
Route::group(['prefix' => 'v1'], function () {
//для тач-панели
Route::get('/xml', 'ApiController@createXML');

    //загрузка справочников и городов
    Route::get('/cities', 'ApiController@getCities');
    Route::get('/city', 'ApiController@getCityByGeo');
    Route::get('/cities/model/exists', 'ApiController@getModelExistsCity');

    Route::get('resources/{data_source}', 'ApiController@getChanges');

    //Пользователь авторизован и получает город
    Route::middleware('jwt.auth')->get('/city/set', 'ApiController@setCity');
  

    //отдаем главную(ленту)
    Route::get('/feed', 'ApiController@index');

    Route::get('/error', 'ApiController@error');
    Route::get('/success', 'ApiController@success');

    //Неавторизованные и заблокированные пользователи
    Route::group(['namespace' => 'Api\v1'], function () {
        //смотрим рецепты и лайфхаки
        Route::get('/recipes/{id}', 'RecipeController@show');
        Route::get('/lifehacks/{id}', 'LifehackController@show');

        //топ 20 пользователей
        Route::get('users/rating', 'UserController@getUsersRating');

        //информация по юзеру
        Route::get('users', 'UserController@profile');

        //получить комментарии
        Route::get('/{data_source}/{id}/comments', 'UserController@getComments');

        //пожаловаться на комментарий
        Route::get('/comments/{id}/complain', 'UserController@complainComment');

        //служебная функциональность: сменить товары партнеров + магазины
        Route::get('/products/change', 'QrController@changeProducts');
        Route::get('/markets/change', 'QrController@changeMarkets');

    });

    //авторизация
    Route::group(['namespace' => 'Auth'], function () {
        Route::get('/login/{provider}', 'SocialController@redirectToProvider');
        Route::get('/login/{provider}/callback', 'SocialController@handleProviderCallback');
        //Route::get('/refresh', 'SocialController@refresh')->name('auth.refresh');
    });

    //Авторизованные активные пользователи
    Route::group(['middleware' => ['jwt.auth', 'isActive']], function () {
        //Route::get('/logout', 'SocialController@logout')->name('auth.logout');
        //сохраняем ресуры
        Route::group(['namespace' => 'Api\v1'], function () {
            Route::post('/recipes/create', 'RecipeController@store');
            Route::post('/lifehacks/create', 'LifehackController@store');

            //добавляем лайки
            Route::post('/{data_source}/{id}/likes/add', 'UserController@addLike');

            //отправляем qr
            Route::any('/qr/add', 'QrController@addQr');

            //отправляем комментарии
            Route::post('/{data_source}/{id}/comments/add', 'UserController@addComment');

            //отправляем обращение
            Route::post('/contacts/add', 'UserController@addContact');

            //добавляем в избранное
            Route::post('/{data_source}/{id}/favourites/add', 'UserController@addFavourite');

            //получаем избранные посты юзера
            Route::get('users/favourites', 'UserController@getFavourite');

        });
    });
});


