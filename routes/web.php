<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
Route::redirect('/', 'login', 301);
Route::group(['namespace' => 'Admin'], function () {
    Route::resource('/recipes', 'RecipeController');
    Route::resource('/lifehacks', 'LifehackController');
    Route::resource('/users', 'UserController');
    Route::resource('/comments', 'CommentController');
    Route::resource('/contacts', 'ContactController');
    Route::resource('/contents', 'ContentController');
    Route::resource('/products', 'ProductController');
    Route::any('/products/{partner}', 'ProductController@uploadImage')->name('products.uploadImage');

    Route::get('/recipes/{recipe}/chief', 'RecipeController@publicAsChiefCooker')->name('recipes.chief.cooker');

    Route::resource('/xml', 'XmlController');
    Route::get('/xml/{id}/add', 'XmlController@add')->name('xml.add');

    Route::get('/contacts/{contact}/answered/', 'ContactController@answered')->name('contacts.answered');
    Route::get('/comments/{comment}/approve', 'CommentController@approve')->name('comments.approve');
    Route::get('/users/{user}/change/city', 'UserController@changeCity')->name('users.changeCity');
    Route::any('/users/{user}/block', 'UserController@blocking')->name('users.block');
    Route::get('/users/{user}/details', 'UserController@details')->name('users.details');
    Route::get('/results/{month}', 'UserController@results')->name('results');
});
