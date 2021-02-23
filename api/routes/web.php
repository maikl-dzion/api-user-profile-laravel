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

$verAlias = '/v1';

Route::get('/',
    ['uses' => 'MainController@index']);

// ________ GET ________

Route::get($verAlias . '/get/user/{value}/{fname}',
    ['uses' => 'MainController@getUser']);

Route::get($verAlias . '/get/user-by-id/{user_id}',
    ['uses' => 'MainController@getUserById']);

Route::get($verAlias . '/get/users',
    ['uses' => 'MainController@getUsers']);

// Пользователь забыл пароль
Route::get($verAlias . '/user/forgot-password/{email}',
    ['uses' => 'MainController@userForgotPassword']);

// Заверить почту
Route::get($verAlias . '/user/email-verify/{type}/{user_id}/{code}',
    ['uses' => 'MainController@emailVerify']);

// Заверить телефон
Route::get($verAlias . '/user/phone-verify/{type}/{user_id}/{code}',
    ['uses' => 'MainController@phoneVerify']);

// Получить путь к загруженным файлам
Route::get($verAlias . '/root/files/path',
    ['uses' => 'MainController@getRootFilesPath']);

// Получить файлы пользователя
Route::get($verAlias . '/user/get-files/{user_id}',
    ['uses' => 'MainController@getUserFiles']);

// Получить avatar пользователя
Route::get($verAlias . '/user/get-avatar/{user_id}',
    ['uses' => 'MainController@getUserAvatar']);

// ________ POST ________
Route::post($verAlias . '/post/user/register',
    ['uses' => 'MainController@userCreate']);

Route::post($verAlias . '/post/auth/login',
    ['uses' => 'MainController@login']);

// Загрузить файлы  пример: type = user id = user_id
Route::post($verAlias . '/post/upload-files/{type}/{id}',
    ['uses' => 'MainController@uploadFiles']);

// Загрузить 1 файл  пример: type = avatar id = user_id
Route::post($verAlias . '/post/upload-single-file/{type}/{id}',
    ['uses' => 'MainController@uploadSingleFile']);

// ________ PUT ________
// Обновить данные пользователя
Route::put($verAlias . '/post/user/update/{user_id}',
    ['uses' => 'MainController@userUpdate']);

// Изменить пароль пользователя
Route::put($verAlias . '/post/user/change-password/{user_id}',
    ['uses' => 'MainController@changePassword']);


// ________ DELETE ________
// Удалить файл
Route::delete($verAlias . '/delete/file/{file_id}',
    ['uses' => 'MainController@deleteFile']);

// Удалить пользователя
Route::delete($verAlias . '/delete/user/{user_id}',
    ['uses' => 'MainController@deleteUser']);


//-------- OLD URL
Route::get($verAlias . '/go/{rand_str}',
    ['uses' => 'MainController@redirectUrl']);

Route::get($verAlias . '/get/links/{user_id}',
    ['uses' => 'MainController@getLinksByUserId']);

//--------------------------------
Route::post($verAlias . '/post/add/link',
    ['uses' => 'MainController@addLink']);

Route::put($verAlias . '/post/update/link/{link_id}',
    ['uses' => 'MainController@updateLink']);

Route::delete($verAlias . '/post/delete/link/{link_id}',
    ['uses' => 'MainController@deleteLink']);

