<?php

namespace app\controllers;

use GuzzleHttp\Client;

class TaxController extends \yii\web\Controller
{

    public function actionAuth()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $client = new Client();
        $url = 'https://api.ofd.uz/auth/login';

        $res = $client->request('POST', $url, [
            'json' => [
                "username" => '514216848',
                "password" => 'p19swTe8C1s_fwNXrTIlRBNecEHdnkqI'
            ],
            'verify' => false
        ]);

        $json = json_decode($res->getBody()->getContents(), true);
        $path = dirname(__DIR__, 1) . '/web/data/taxAuth';

        if (isset($json['access_token'])){
            file_put_contents($path, $json['access_token']);
        }
        return $json;
    }

    public function getToken()
    {
        $token = file_get_contents(dirname(__DIR__, 1) . '/web/data/taxAuth');
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



    public function actionGetCommitent($tin)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $url = "https://api.ofd.uz/emr-api/catalog/commitent/list/" . $tin;
        $res = $this->getResponse($url);
        $commitents = json_decode($res->getBody()->getContents(), true);

        return $commitents;
    }

    public function actionReportCommitent($tin)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $url = "https://api.ofd.uz/emr-api/catalog/commitent/list/" . $tin;
        $res = $this->getResponse($url);
        $commitents = json_decode($res->getBody()->getContents(), true)["data"];

        require('../vendor/PHPExcel/PHPExcel.php');
        $objPHPExcel = new \PHPExcel;
        $url = './excel/commitent.xlsx';
        $objPHPExcel = \PHPExcel_IOFactory::load($url);
        $sheet = 0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $activeSheet = $objPHPExcel->getActiveSheet();
        $row = 3;
        $activeSheet->setCellValueExplicit('B1', 'STIR: ' . $tin . ' bo\'lgan tashkilotning barcha kammitentlar ro\'yxati', \PHPExcel_Cell_DataType::TYPE_STRING);

        foreach ($commitents as $commitent) {
            $activeSheet->setCellValueExplicit('A'.$row, $row-2, \PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('B'.$row, str_replace('"', '', $commitent["companyName"]), \PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('C'.$row, $commitent["tin"], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $activeSheet->setCellValueExplicit('D'.$row, $commitent["contractBeginDate"], \PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('E'.$row, $commitent["contractEndDate"], \PHPExcel_Cell_DataType::TYPE_STRING);
            $row++;
        }


        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,  "Excel2007");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="report_'. date("Y-m-d_h:i:s") .'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;

        return $commitents;
    }

}
