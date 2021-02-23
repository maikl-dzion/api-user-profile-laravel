<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;

class ShortLinkController extends Controller
{

    public function __construct(){
        $this->model = new ShortLink();
    }

    public function addLink($request){

        if(!isset($request['code']))
             $request['code'] = $this->createShortLink();

        try {
            $result = $this->model->insert($request);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return ['save_result' => $result];
    }

    public function updateLink($linkId, $request){
        $result = $this->model->where('link_id', $linkId)->update($request);
        return ['save_result' => $result];
    }

    public function deleteLink($linkId){
        $link = $this->model->find($linkId);
        $result = $link->delete();
        return ['save_result' => $result];
    }

    public function getLinksByUserId($userId){
        $result = $this->model->where('user_id', $userId)->get();
        return ['result' => $result];
    }

    protected function createShortLink() {
        $link = explode('/', $_SERVER['SCRIPT_NAME']);
        array_pop($link);
        $link = implode('/', $link);
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffle = substr(str_shuffle($permitted_chars), 0, 8);
        $shortLink = $_SERVER['SERVER_NAME'] . $link . '/v1/go/' . $shuffle;
        return $shortLink;
    }

    public function getRealUrl($code){
        $record = $this->model->where('code', $code)->first();
        $result = $record->toArray();
        if(isset($result['link']))
            $result = $result['link'];
        return ['result' => $result];
    }
}
