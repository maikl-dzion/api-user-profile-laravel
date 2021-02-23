<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ApiResourceController extends Controller
{
    protected $model;
    protected $modelId;

    public function __construct($model = null) {
         if($model) {
             $this->model = $model;
             $this->modelId = $this->model->getPrimaryKey();
         }
    }

    public function create($request) {
        $result = $error = [];
        try {
            $result = $this->model->insert($request);
        } catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
            $error['message'] = $errorMessage;
        }
        return $this->getResponse($result, $error);
    }

    protected function update($request, $itemId) {
        $result = $error = [];
        try {
            $result = $this->model->where($this->modelId, $itemId)->update($request);
        } catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
            $error['message'] = $errorMessage;
        }
        return $this->getResponse($result, $error);
    }

    public function getItems($limit = 0) {
        $records = $this->model->all();
        if(empty($records))
            return [];
        $items = $records->toArray();
        return $items;
    }

    public function deleteItem($itemId)
    {
        $result = $this->model->where($this->modelId, $itemId)->delete();
        return $result;
    }

    public function getItem($fname, $value) {
        $record = $this->model->where($fname, $value);
        if(empty($record))
            return [];
        $item = $record->first()->toArray();
        return $item;
    }

    public function getItemById($itemId) {
        $record = $this->model->where($this->modelId, $itemId);
        if(empty($record))
            return [];
        $item = $record->first()->toArray();
        return $item;
    }

    public function getResponse($result = [], $error = []) {
        $status = (empty($error)) ? true : false;
        return [
            'result' => $result,
            'error'  => $error,
            'status' => $status,
        ];
    }

    // public function show($id){}
    // public function index(){}
}
