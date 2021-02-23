<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{

    const JWT_TOKEN_NAME  = 'X-User-Jwt-Token';
    protected $requestHeaders = [];
    protected $user;
    protected $request;

    public function __construct(Request $request){

        $this->request = $request;
        $this->user = new UserController();
        $this->link = new ShortLinkController();
        $this->setRequestHeaders();
        // $this->userCheckAccessToken();

    }

    public function respond($data, $code = 200 ,$headers = []) {
        return response()->json($data, $code);
    }

    protected function getToken($tokenName = self::JWT_TOKEN_NAME) {
        $token = null;
        if(!empty($this->requestHeaders[$tokenName]))
            $token = trim($this->requestHeaders[$tokenName]);
        return $token;
    }

    protected function setRequestHeaders() {
        $this->requestHeaders = getallheaders();
    }

    protected function getHeaders() {
       return $this->requestHeaders;
    }

    protected function userCheckAccessToken() {

        $token = $this->getToken();
        $result = $this->user->userCheckAccessToken($token);

        if(empty($result)) {
//            if ( !$this->request->is('v1/post/auth/login') &&
//                 !$this->request->is('v1/post/user/register') ) {
//                  die(json_encode(['error' => 'Токен пользователя недействительный']));
//            }
        }

        if(isset($result['password']))
            unset($result['password']);
        $this->user = $result;
    }
}
