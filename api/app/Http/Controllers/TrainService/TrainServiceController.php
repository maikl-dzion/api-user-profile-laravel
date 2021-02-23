<?php

namespace App\Http\Controllers\TrainService;

use SoapClient;

class TrainServiceController {

    protected $wdsl = "https://api.starliner.ru/Api/connect/Soap/Train/1.0.0?wsdl";
    protected $client;

    protected $route = 'train-list';
    protected $method;
    protected $request;
    protected $train = '019У';

    protected $travelInfo =[
        'from'   => 'Санкт-Петербург',
        'to'     => 'Москва',
        'day'    => 9,
        'month'  => 3,
    ];

    protected $routes = [
        'train-route' => 'getTrainRoute',
        'cities'      => 'getCities',
        'train-list'  => 'getTrainList',
    ];

    protected $auth = [
        'login' => 'test',
        'psw' => 'bYKoDO2it',
        'terminal' => 'htk_test',
        'represent_id' => 22400,
    ];

    public function __construct() {
        $this->getRequest();
        $this->client = new SoapClient($this->wdsl);
    }

    protected function getRequest() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if(!empty($_SERVER['PATH_INFO']))
            $this->route = trim($_SERVER['PATH_INFO'], '/');
        $this->request = (array)json_decode(file_get_contents('php://input'));
    }

    public function getTrainList() {
        $travelInfo = $this->travelInfo;
        $results    = $this->client->timeTable($this->auth, $travelInfo);
        return $results;
    }

    public function getTrainRoute() {
        $this->getTravelInfo();
        $results = $this->client->trainRoute($this->auth, $this->train, $this->travelInfo);
        return $results;
    }

    public function getCities() {
        $filter = '';
        $results = $this->client->getCities($this->auth, $filter);
        return $results;
    }

    protected function getTravelInfo() {

        if ($this->route == 'train-route' && !empty($this->request))
            $this->travelInfo = $this->request;

        $this->train = $this->travelInfo['train'];
        unset($this->travelInfo['train']);
    }

}
