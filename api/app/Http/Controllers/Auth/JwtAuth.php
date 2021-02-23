<?php

namespace App\Http\Controllers\Auth;

//use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;

class JwtAuth
{

    public function createToken($data) {
        $header    = array('alg' => 'HS256', 'type' => 'JWT');
        $payload   = $data;
        $secretKey = $this->getSecretKey();

        $encodedHeader  = base64_encode(json_encode($header));
        $encodedPayload = base64_encode(json_encode($payload));
        $headerPayloadCombined = $encodedHeader . '.' . $encodedPayload;
        $hash      = hash_hmac('sha256', $headerPayloadCombined, $secretKey,true);
        $signature = base64_encode($hash);
        $jwtToken = $headerPayloadCombined . '.' . $signature;
        return $jwtToken;
    }

    public function verifyToken($recievedJwt) {

        $secretKey   = $this->getSecretKey();
        $jwtValues = explode('.', $recievedJwt);

        if(empty($jwtValues[0]) || empty($jwtValues[1]) || empty($jwtValues[2]))
            return false;

        $recievedSignature     = $jwtValues[2];
        $recievedHeaderPayload = $jwtValues[0] . '.' . $jwtValues[1];
        $hash = hash_hmac('sha256', $recievedHeaderPayload, $secretKey, true);
        $comparySignatureShould = base64_encode($hash);

        if($comparySignatureShould === $recievedSignature) {
            $data = (array)json_decode(base64_decode($jwtValues[1]));
            return $data;
        }

        return false;
    }

    private function getSecretKey() {
        $secret = 'secret_key';
        return $secret;
    }

}
