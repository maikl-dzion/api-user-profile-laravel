<?php

namespace App\Http\Controllers\SendMessageService;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\SendMessageServiceInterface;
use App\Http\Controllers\SendMessageService\SMSRU;

class SendMessageController extends Controller implements SendMessageServiceInterface
{
    protected $sendSmsResponse;
    protected $sendMailResponse;
    protected $serviceName;
    protected $apiKey = 'CBB553A9-4DB0-0B73-3206-F13194F05516';

    // public function __construct(){}

    public function sendMessage($ident, $message, $service = 'mail', $subject = 'Ссылка для регистрации') {
        $this->serviceName = $service;

        switch ($service) {
            case 'mail' :
                $this->_sendMail($ident, $message, $subject);
                break;

            case 'phone' :
                $this->_sendSms($ident, $message, $subject);
                break;
        }
        return $this->checkSendResponse();
    }

    public function getResponse() {
        if($this->serviceName == 'phone') {
            return $this->sendSmsResponse;
        } elseif($this->serviceName == 'mail') {
            return $this->sendMailResponse;
        }
    }

    ///////////////////////////////
    //////////////////////////////
    // ---- ЗАКРЫТЫЕ МЕТОДЫ ------

    protected function checkSendResponse() {
        $result = false;
        switch($this->serviceName) {
            case 'phone' :
                if ($this->sendSmsResponse['status'] == "OK")
                    $result = true;
                break;

            case 'mail' :
                if ($this->sendMailResponse)
                    $result = true;
                break;
        }
        return $result;
    }

    protected function _sendMail($email, $message, $subject = 'Ссылка для регистрации') {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        // $headers .= "From: birthday@example.com\r\n";
        $response = mail($email, $subject, $message, $headers);
        $this->sendMailResponse = $response;
        return $this->sendMailResponse;
    }

    protected function _sendSms($phone, $message, $subject = '') {

        $sms = new SMSRU($this->apiKey);
        $data = new \stdClass();
        $data->to   = $phone;
        $data->text = $message; // Текст сообщения

        // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
        // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему

        $response = $sms->send_one($data); // Отправка сообщения и возврат данных в переменную
        $this->sendSmsResponse = (array)$response;
        return $this->sendSmsResponse;
    }

//    protected function checkSendSms() {
//        if ($this->sendSmsResponse['status'] == "OK")
//            return true;
//        return false;
//    }
//
//    protected function checkSendMail() {
//        if ($this->sendMailResponse)
//            return true;
//        return false;
//    }

}

