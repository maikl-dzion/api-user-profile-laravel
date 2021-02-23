<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiResourceController;

class MainController extends FrontController
{

    public function index(){
        echo 'MainIndex';

//        $model = new \App\Models\BulletinBoard;
//
//        $api = new ApiResourceController($model);
//
//
//        $newItem = [
//            'title'       => 'Продам машину',
//            'category_id' => 23,
//            'price'       => '234.000',
//            'description' => 'Продам внедорожник',
//        ];
//
//        $item = $api->create($newItem);
//
//        $items = $api->getItems();
//
//        $result = [
//            $item,
//            $items,
//        ];
//
//        dd($result);
        // return $this->respond($data, 200);
    }

    // Регистрация пользователя
    public function userCreate(Request $request) {
        $data = $request->all();
        $result = $this->user->userCreate($data);
        return $this->respond($result, 200);
    }

    // Обновление пользователя
    public function userUpdate($userId, Request $request) {
        $data = $request->all();
        $result = $this->user->userUpdate($userId, $data);
        return $this->respond($result, 200);
    }

    // Аутентификация пользователя
    public function login(Request $request) {

        $email    = $request->input('email');
        $password = $request->input('password');
        $result = $this->user->login($email, $password);
        return $this->respond($result, 200);
    }

    // Изменение пароля пользователя
    public function changePassword($userId, Request $request) {
        $newPassword    = $request->input('password');
        $result = $this->user->changePassword($userId, $newPassword);
        return $this->respond($result, 200);
    }

    // Пользователь забыл пароль
    public function userForgotPassword($email) {
        $result = $this->user->userForgotPassword($email);
        return $this->respond($result, 200);
    }

    // Получение пользователя
    public function getUser($userId, $fname = 'user_id') {
        $result = $this->user->getUser($userId, $fname);
        return $this->respond($result, 200);
    }

    // Получение пользователя по user_id
    public function getUserById($userId) {
        $result = $this->user->getUserById($userId);
        return $this->respond($result, 200);
    }

    // Получение всех пользователей
    public function getUsers() {
        $result = $this->user->getUsers();
        return $this->respond($result, 200);
    }

    // Подтвердить почту
    public function emailVerify($type, $userId, $code = '') {
        $result = $this->user->emailVerify($type, $userId, $code);
        return $this->respond($result, 200);
    }

    // Подтвердить телефон
    public function phoneVerify($type, $userId, $code = '') {
        $result = $this->user->phoneVerify($type, $userId, $code);
        return $this->respond($result, 200);
    }

    // Загрузка файлов
    public function uploadFiles($type, $id, Request $request) {
        $data = $request->all();
        $result = false;
        switch ($type) {
            case 'user' : // Загрузка файлов пользователем
                $result = $this->user->userUploadFiles($id, $data);
                break;
        }
        return $this->respond($result, 200);
    }

    // Загрузка 1 файла
    public function uploadSingleFile($type, $id, Request $request) {
        $data = $request->all();
        $result = false;
        switch ($type) {
            case 'avatar' : // Загрузка изображения пользователя
                $result = $this->user->userUploadAvatar($id, $data);
                break;
        }
        return $this->respond($result, 200);
    }

    // Получить файлы пользователя
    public function getUserFiles($userId) {
        $result = $this->user->getUserFiles($userId);
        return $this->respond($result, 200);
    }

    // Удалить файл
    public function deleteFile($fileId) {
        $result = $this->user->deleteFile($fileId);
        return $this->respond($result, 200);
    }

    // Удалить пользователя
    public function deleteUser($userId) {
        $result = $this->user->deleteUser($userId);
        return $this->respond($result, 200);
    }

    public function getUserAvatar($userId) {
        $result = $this->user->getUserAvatar($userId);
        return $this->respond($result, 200);
    }


    ////////////////////////////////////
    ///

    // Добавление новой ссылки
    public function addLink(Request $request) {
        $data = $request->all();
        $result = $this->link->addLink($data);
        return $this->respond($result, 200);
    }

    // Изменение ссылки
    public function updateLink($linkId, Request $request) {
        $data = $request->all();
        $result = $this->link->updateLink($linkId, $data);
        return $this->respond($result, 200);
    }

    // Удаление ссылки
    public function deleteLink($linkId) {
        $result = $this->link->deleteLink($linkId);
        return $this->respond($result, 200);
    }

    // Получение ссылок по user_id
    public function getLinksByUserId($userId) {
        $result = $this->link->getLinksByUserId($userId);
        return $this->respond($result, 200);
    }

    public function redirectUrl($randomUrl) {
        $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $result = $this->link->getRealUrl($url);
        $realLink = $result['result'];
        // header('Location: http://' . $realLink);
        header('Location: ' . $realLink);
    }

}
