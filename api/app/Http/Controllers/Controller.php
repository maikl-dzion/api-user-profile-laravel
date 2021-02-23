<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function saveResponse($data) {
        return ['save_result' => $data];
    }

    protected function errorResponse($data) {
        return ['error' => $data];
    }

    protected function sendMail($to, $message, $subject = 'Письмо с сайта', $headers = []) {

        if(empty($headers))
            $headers = [
                'From'     => 'webmaster@example.com',
                'Reply-To' => 'webmaster@example.com',
                'X-Mailer' => 'PHP/' . phpversion()
            ];

        return mail($to, $subject, $message, $headers);
    }

    protected function createRandomString($count = 8) {
        $permittedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result= substr(str_shuffle($permittedChars), 0, $count);
        return $result;
    }

    protected function getRootFilesPath($pathName = '') {
        $paths = explode('/', trim($_SERVER['PHP_SELF'], '/'));
        array_pop($paths);
        $serverHost = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $path = implode('/', $paths);
        $rootPath = $serverHost . '/' . $path .'/storage/app/';
        return ['root_path' => $rootPath];
    }
}
