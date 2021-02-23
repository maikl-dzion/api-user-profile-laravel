<?php

namespace App\Http\Controllers\FilesUploader;

use App\User;

class FilesUploadController extends Controller
{
    protected $model;

    public function __construct(){
        $this->model = new User();
    }

    public function uploder($userId, $request) {
        $result = $this->model->where('user_id', $userId)->update($request);
        return $result;
    }

}
