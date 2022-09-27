<?php

namespace app\controllers;

use GuzzleHttp\Client;
use yii\web\Controller;

class SchemeController extends Controller
{
    public function actionAuth()
    {
        $client = new Client();
        $url = 'https://cabinet.cultureticket.uz/api/CultureTicket/Token';

        $res = $client->request('POST', $url, [
            'json' => [
                "login" => 'umar@iticket.uz',
                "password" => '123456'
            ],
            'verify' => false
        ]);

        $json = json_decode($res->getBody()->getContents(), true);
        $path = dirname(__DIR__, 1) . '/web/data/auth';

        if (isset($json['result']['accessToken'])){
            file_put_contents($path, $json['result']['accessToken']);
        }

        return $json;
    }

    public function getToken()
    {
        $token = file_get_contents(dirname(__DIR__, 1) . '/web/data/auth');
        return $token;
    }

    public function getResponse($url) {
        $client = new Client();
        $res = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "Bearer " . $this->getToken(),
            ],
            'verify' => false
        ]);

        return $res;
    }

    public function actionSvg($hallId)
    {
        $url ='https://cabinet.cultureticket.uz/api/CultureTicket/PalaceHall/' . $hallId;
        $res = $this->getResponse($url);
        $hall = json_decode($res->getBody()->getContents(), true);

        $path = dirname(__DIR__, 1) . '/web/svg/test.svg';
        if ($hall["result"]["svgText"]) {
            file_put_contents($path, $hall["result"]["svgText"]);
            echo  "Success";
        } else {
            echo "Error";
        }

        return $this->render('scheme',[
            'svg' => $hall["result"]["svgText"]
        ]);
//        var_dump($hall["result"]["svgText"]);die();
//        return $res->getBody()->getContents();
    }

}