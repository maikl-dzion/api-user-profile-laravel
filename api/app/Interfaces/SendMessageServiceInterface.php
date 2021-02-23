<?php

namespace App\Interfaces;

interface SendMessageServiceInterface {
    public function sendMessage($ident, $message, $serviceName, $subject);
    public function getResponse();
}


