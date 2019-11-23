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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/login/phone', 'Auth\LoginController@phone')->name('login.phone');
Route::post('/login/phone', 'Auth\LoginController@verify');

Route::get('/verify{token}', 'Auth\RegisterController@verify')->name('register.verify');

Route::group([
    'middleware' => ['auth'],
    'namespace' => 'Cabinet',
    'prefix' => 'cabinet',
    'as' => 'cabinet.',
], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'profile', 'as' => 'profile.',], function () {
        Route::get('/', 'ProfileController@index')->name('home');
        Route::get('/edit', 'ProfileController@edit')->name('edit');
        Route::put('/update', 'ProfileController@update')->name('update');
        Route::post('/phone', 'PhoneController@request');
        Route::get('/phone', 'PhoneController@form')->name('phone');
        Route::put('/phone', 'PhoneController@verify')->name('phone.verify');

        Route::post('/auth', 'PhoneController@auth')->name('phone.auth');
    });

    Route::resource('adverts', 'AdvertController');
});



Route::group([
        'middleware' => ['auth', 'can:admin-panel'],
        'namespace' => 'Admin',
        'prefix' => 'admin',
        'as' => 'admin.',
    ], function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::resource('users', 'UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');

        Route::resource('regions', 'RegionController');

        Route::group(['namespace' => 'Adverts','prefix' => 'adverts','as' => 'adverts.'], function () {
                Route::resource('categories', 'CategoryController');

                Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], function () {
                    Route::post('/first', 'CategoryController@first')->name('first');
                    Route::post('/up', 'CategoryController@up')->name('up');
                    Route::post('/down', 'CategoryController@down')->name('down');
                    Route::post('/last', 'CategoryController@last')->name('last');
                    Route::resource('attributes', 'AttributeController')->except('index');
                });
        });
    });

