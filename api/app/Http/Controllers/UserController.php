<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\StoreFile;
use App\Http\Controllers\Auth\JwtAuth;
use App\Http\Controllers\SendMessageService\SendMessageController;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $model;

    public function __construct(){
        $this->model = new User();
        $this->jwt   = new JwtAuth();
    }

    public function getUser($value, $fname = 'user_id') {
        $record = $this->model->where($fname, $value);
        if(empty($record))
            return [];
        $user = $record->first()->toArray();
        return $user;
    }

    // Получение пользователя по user_id
    public function getUserById($userId) {
        $record = $this->model->where('user_id', $userId);
        if(empty($record))
            return [];
        $user = $record->first()->toArray();
        return $user;
    }

    public function getUsers() {
        $records = $this->model->all();
        if(empty($records))
            return [];
        $users = $records->toArray();
        return $users;
    }

    // Регистрация пользователя
    public function userCreate($request){

        if(!empty($request['password']))
            $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);

        try {
            $result = $this->model->insert($request);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

        return $this->saveResponse($result);
    }

    // Обновление пользователя
    public function userUpdate($userId, $request){
        return $this->update($userId, $request);
    }

    // Обновление пароля пользователя
    public function changePassword($userId, $newPassword){
        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashPassword]);
    }

    public function login($email, $password){

        $record = $this->model->where('email', $email)->first();

        if(empty($record))
            return false;

        $user = $record->toArray();
        $passwordHash = $user['password'];

        if (!password_verify($password, $passwordHash))
             return false;

        $token = $this->jwt->createToken($user);
        $status = true;

        return [
            'user'   => $user,
            'token'  => $token,
            'status' => $status,
        ];
    }

    public function userCheckAccessToken($token) {
        $result = $this->jwt->verifyToken($token);
        if (empty($result))
            return false;
        return $result;
    }

    // Забыли пароль ?
    public function userForgotPassword($email) {
        $user = $this->getUser($email, 'email');
        if(empty($user))
            return $this->errorResponse("Пользователь с таким email={$email} не существует");

        $newPassword = '1234';
        $message = "Ваш новый пароль- {$newPassword}";

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $userId = $user['user_id'];
        $result = $this->update($userId, ['password' => $passwordHash]);
        $mail = $this->sendMail($email, $message, 'Сброс пароля');
        if(!$result || !$mail)
            return $this->errorResponse('Произошла ошибка,попробуйте еще раз');
        return $this->saveResponse($result);
    }

    // Заверяем почту
    public function emailVerify($type, $userId, $code = '') {
        $newCode = $this->createRandomString(4);
        $user = $this->getUser($userId);
        $result = false;

        switch ($type) {

            case 'send'  :
                $email = $user['email'];
                $send = new SendMessageController();
                $mail  = $send->sendMessage($email, $newCode, 'mail', 'Подтверждение почты');
                // $result = $this->sendMail($email, $code, 'Заверяем почту');
                if($mail) {
                    $save = $this->update($userId, ['email_code' => $newCode]);
                    if(!empty($save))
                        $result = true;
                }
                break;

            case 'check' :
                $emailCode = $user['email_code'];
                if(trim($emailCode) == trim($code) ) {
                    $this->update($userId, ['email_verify' => 1]);
                    $user = $this->getUser($userId);
                    if(!empty($user['email_verify'])) {
                        $result = $user['email_verify'];
                    }
                }
                break;
        }
        return $result;
    }

    // Заверяем телефон
    public function phoneVerify($type, $userId, $code = '') {
        $newCode = $this->createRandomString(4);
        $user = $this->getUser($userId);
        $result = false;

        switch ($type) {

            case 'send'  :
                $phone = $user['phone'];
                $send = new SendMessageController();
                $send  = $send->sendMessage($phone, $newCode, 'phone', 'Подтверждение номера телефона');
                if(!empty($send)) {
                    $save = $this->update($userId, ['phone_code' => $newCode]);
                    if(!empty($save))
                        $result = true;
                }
                break;

            case 'check' :
                $phoneCode = $user['phone_code'];
                if(trim($phoneCode) == trim($code) ) {
                    $this->update($userId, ['phone_verify' => 1]);
                    $user = $this->getUser($userId);
                    if(!empty($user['phone_verify'])) {
                        $result = $user['phone_verify'];
                    }
                }
                break;
        }
        return $result;
    }

    // Загрузка файлов
    public function userUploadFiles($userId, $request) {

        $folderName = $request['folder_name'];
        $files = $request['files'];
        $filesPath = 'users/u_' . $userId;
        $insertData = [];
        foreach ($files as $key => $file) {
            $path = $file->store($filesPath);
            $item = [
                'user_id'       => $userId,
                'folder_name'   => $folderName,
                'path'          => $path,
                'resource_name' => 'user',
            ];
            $insertData[] = $item;
        }

        $resp = StoreFile::insert($insertData);

        return $this->saveResponse($resp);
    }

    // Загрузка фото пользователя
    public function userUploadAvatar($userId, $request) {
        $file = $request['file'];
        $filesPath = 'users/u_' . $userId;
        $path = $file->storeAs($filesPath, 'user_avatar.jpg');
        //$resp = StoreFile::insert($insertData);
        return $this->saveResponse($path);
    }

    // Получить файлы пользователя
    public function getUserFiles($userId) {
        $record = StoreFile::where('user_id', $userId);
        if(empty($record))
            return [];
        $result = $record->get();
        return $result;
    }

    // Получить avatar пользователя
    public function getUserAvatar($userId) {
        $rootUrl = $this->getRootFilesPath();
        $userPath = 'users/u_' . $userId .'/user_avatar.jpg';
        $exist = Storage::disk('local')->exists($userPath);
        if(!$exist)
           return false;
        $file = $rootUrl['root_path'] . $userPath;
        return $file;
    }

    // Удалить файл
    public function deleteFile($fileId) {
        $result = StoreFile::where('file_id', $fileId)->delete();
        return $this->saveResponse($result);
    }

    // Удалить пользователя
    public function deleteUser($userId) {
        $result = $this->model->where('user_id', $userId)->delete();
        return $this->saveResponse($result);
    }

    protected function update($userId, $request) {
        try {
            $result = $this->model->where('user_id', $userId)->update($request);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
        return $this->saveResponse($result);
    }

}
