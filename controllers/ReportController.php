<?php

namespace app\controllers;

use GuzzleHttp\Client;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        $this->actionAuth();
        $types = ['Театр', 'Музей'];

        if ($this->request->isPost)
        {
//            var_dump($this->request->post());die();
            set_time_limit(10 * 60);
            $this->actionAuth();

            require('../vendor/PHPExcel/PHPExcel.php');
            $objPHPExcel = new \PHPExcel;
            $url = './excel/period_report.xlsx';
            $objPHPExcel = \PHPExcel_IOFactory::load($url);
            $sheet = 0;
            $objPHPExcel->setActiveSheetIndex($sheet);
            $activeSheet = $objPHPExcel->getActiveSheet();
            $row = 3;

            $startDate = $this->request->post("from_date");
            $endDate = $this->request->post("to_date");
            $orgs = $this->request->post('orgs');
            $type = $this->request->post('types');
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $soldTicketsUrl = 'https://cabinet.cultureticket.uz/ocelot/api-admin/Report/TicketOrder';
//            $orgs = $this->getTheatres();
            $activeSheet->setCellValueExplicit('B1', $startDate . " - " . $endDate, \PHPExcel_Cell_DataType::TYPE_STRING);
            $result = [];

            foreach ($orgs as $org)
            {
                $i = 0;
                $sum = 0;
                $res = $this->getResponsePost($soldTicketsUrl, +$org, $startDate, $endDate);
                $tickets = json_decode($res->getBody()->getContents(), true);

                foreach ($tickets["result"]["data"] as $ticket)
                {
                    if (!$ticket["isReject"]) {
                        $i++;
                        $sum += $ticket['ticketRealSum'];
                    }
                }
//                array_push($result, [
//                    'name' => $org["name"],
//                    'count' => $i,
//                    'sum'   => $sum
//                ]);
                $activeSheet->setCellValueExplicit('A'.$row, $this->getOrgName(+$org, $type), \PHPExcel_Cell_DataType::TYPE_STRING);
                $activeSheet->setCellValueExplicit('B'.$row, $i, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $activeSheet->setCellValueExplicit('C'.$row, $sum, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $row++;
            }

//            return $result;
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,  "Excel2007");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="report_'.$startDate . '-' . $endDate .'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        }
        return $this->render('index',[
            'types' => $types
        ]);
    }

    public function actionAuth()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $client = new Client();
        $url = 'https://cabinet.cultureticket.uz/api/CultureTicket/Token';

        $res = $client->request('POST', $url, [
            'json' => [
                "login" => 'fond',
                "password" => 'p12345678#'
            ],
            'verify' => false
        ]);

        $json = json_decode($res->getBody()->getContents(), true);
        $path = dirname(__DIR__, 1) . '/web/data/reportAuth';

        if (isset($json['result']['accessToken'])){
            file_put_contents($path, $json['result']['accessToken']);
        }
        return $json;
    }

    public function getToken()
    {
        $token = file_get_contents(dirname(__DIR__, 1) . '/web/data/reportAuth');
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

    public function getResponsePost($url, $eventOrgId, $startDate, $endDate) {
        ini_set('memory_limit', '8192M');
        $client = new Client();
        $res = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => "Bearer " . $this->getToken(),
                'content-type' => 'application/json'
            ],
            'body' => '{
                    "startDate": "'. $startDate .'",
                    "endDate": "'. $endDate .'",
                    "skip": 0,
                    "take": 1000000000,
                    "isReject": false,
                    "eventOrgId": ' . $eventOrgId . '
                }',
            'verify' => false,
            'content'
        ]);

        return $res;
    }

    public function actionGetOrg()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orgUrl = 'https://cabinet.cultureticket.uz/ocelot/api-admin/Report/TicketEventOrgListForDropdown';
//        $saleOrgUrl = 'https://cabinet.cultureticket.uz/ocelot/api-admin/Report/TicketSaleOrgListForDropdown';
        $res = $this->getResponse($orgUrl);
        $orgs = json_decode($res->getBody()->getContents(), true);

        return $orgs;
    }

    public function actionGetTickets()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $soldTicketsUrl = 'https://cabinet.cultureticket.uz/ocelot/api-admin/Report/TicketOrder';



        $orgs = $this->getTheatres();

        $result = [];

        foreach ($orgs as $org)
        {
            $i = 0;
            $sum = 0;
            $res = $this->getResponsePost($soldTicketsUrl, $org["id"], "06.12.2022", "06.01.2023");
            $tickets = json_decode($res->getBody()->getContents(), true);

            foreach ($tickets["result"]["data"] as $ticket)
            {
                if (!$ticket["isReject"]) {
                    $i++;
                    $sum += $ticket['ticketRealSum'];
                }
            }
            array_push($result, [
                'name' => $org["name"],
                'count' => $i,
                'sum'   => $sum
            ]);
        }



        return $result;
    }

    public function getOrgName($id, $type)
    {
        if ($id != null) {

            if ($type == 0) {
                $orgs = file_get_contents(dirname(__DIR__, 1) . '/web/json/theatres.json');
                $orgs = json_decode($orgs, true);

                foreach ($orgs["result"] as $org) {
                    if ($org["id"] === $id){
                        return $org["name"];
                    }
                }
            }
            if ($type == 1) {
                $orgs = file_get_contents(dirname(__DIR__, 1) . '/web/json/museums.json');
                $orgs = json_decode($orgs, true);

                foreach ($orgs["result"] as $org) {
                    if ($org["id"] === $id){
                        return $org["name"];
                    }
                }
            }

        }
    }

    public function actionGetOrganisations($id)
    {
        if ($id != null) {

            if ($id == 0) {
                $orgs = file_get_contents(dirname(__DIR__, 1) . '/web/json/theatres.json');
                $orgs = json_decode($orgs, true);
                if (count($orgs["result"]) > 0) {
                    foreach ($orgs["result"] as $org) {
                        echo "<option value='" . $org["id"] . "'>" . $org["name"] . "</option>";
                    }
                } else {
                    echo "'<option>-</option>'";
                }
            }
            if ($id == 1) {
                $orgs = file_get_contents(dirname(__DIR__, 1) . '/web/json/museums.json');
                $orgs = json_decode($orgs, true);
                if (count($orgs["result"]) > 0) {
                    foreach ($orgs["result"] as $org) {
                        echo "<option value='" . $org["id"] . "'>" . $org["name"] . "</option>";
                    }
                } else {
                    echo "'<option>-</option>'";
                }
            }

        }
    }

}
