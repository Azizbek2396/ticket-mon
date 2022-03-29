<?php

namespace app\controllers;

use app\models\Events;
use GuzzleHttp\Client;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Saver;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public const EVENTID = 4;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    // public $layout = 'site';
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['scheme']);
        // return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionScheme($id="last")
    {
        if ($id === "last") {
            $id = Events::find()->orderBy(['id' => SORT_DESC])->one()->id;
        }
        if($this->request->post('id')){
            $id = $this->request->post('id');
        }
        $event = Events::findOne($id);

        $path = dirname(__DIR__, 1). "/web/svg/".$event->hall.".svg";
        if(!file_exists($path)) {
            throw new NotFoundHttpException("File not found!");
        }
        $events = ArrayHelper::map(Events::find()->where(['is_active' => 1])->all(), 'id', 'title');

        return $this->render('scheme', [
            'id' => $id,
            'path'      => $path,
            'events'    => $events
        ]);
    }

    public function actionTest()
    {
        $this->layout = "a";
        $models = Saver::find()->where(['event_id'=>$this::EVENTID])->all();



        return $this->render('test', [
            'models' => $models,
        ]);
    }

    public function actionSaver($id)
    {
        foreach (Yii::$app->request->post('seats') as $key => $value) {
            $model = new Saver();
            $model->event_id = $id;
            $model->seat_id = $value['seatid'];
            $model->place_title = $value['title'];
            $model->comment = Yii::$app->request->post('comment');
            $model->color = substr(hash('ripemd160', Yii::$app->request->post('comment')), -6);
            if(Saver::find()->where(['event_id'=>$model->event_id,'seat_id'=>$model->seat_id])->one()){
                $a = 1;
            }
            else{
                $model->save();

            }
            
        }
        return $this->redirect(['scheme', 'id'=>$id] );
        die;
    }

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
        var_dump($json);
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

    public function actionSold()
    {
        $events = Events::find()->all();

        foreach ($events as $event) {
            $url1 ='https://cabinet.cultureticket.uz/api/CultureTicket/SessionTickets/' . $event->session_id;
            $res = $this->getResponse($url1);
            $tickets = json_decode($res->getBody()->getContents(), true);

            $url2 = 'https://cabinet.cultureticket.uz/api/CultureTicket/PalaceHallSeats/' . $event->hall;
            $res2 = $this->getResponse($url2);
            $seats = json_decode($res2->getBody()->getContents(), true);

            $soldTickets = [];
            $rejectedTickets = [];
            foreach ($tickets['result'] as $ticket) {
                if(($ticket['ticketStatusName'] === "Проданный") && ($ticket['tarifName'] !== "Пригласительное место")) {
                    array_push($soldTickets, $ticket);
                }
                if($ticket['ticketStatusName'] === "Возвратный") {
                    array_push($rejectedTickets, $ticket);
                }
            }

            $soldSeats = [];
            $rejectedSeats = [];
            foreach ($soldTickets as $ticket) {
                foreach ($seats['result'] as $seat) {
                    if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                        array_push($soldSeats, $seat);
                    }
                }
            }
            foreach ($rejectedTickets as $ticket) {
                foreach ($seats['result'] as $seat) {
                    if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                        array_push($rejectedSeats, $seat);
                    }
                }
            }

            if (!empty($soldSeats)){
                foreach ($soldSeats as $soldSeat) {
                    $model = new Saver();
                    $model->event_id = $event->id;
                    $model->seat_id = 'seat-' . $soldSeat['svgSeatId'];
                    $model->place_title = 'Sector: ' . $soldSeat['sectorName'] . ' Row: ' . $soldSeat['rowNumber'] . ' Seat: ' . $soldSeat['seatNumber'];
                    $model->comment = 'Проданное место';
                    $model->color = 'CCCCCC';
                    if(Saver::find()->where(['event_id'=>$event->id,'seat_id'=>$model->seat_id])->one()){
                        $a = 1;
                    } else{
                        $model->save();
                    }
                }
            }
            if (!empty($rejectedSeats)) {
                foreach ($rejectedSeats as $rejectedSeat) {
                    if (Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $rejectedSeat['svgSeatId']])->one()){
                        Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $rejectedSeat['svgSeatId']])->one()->delete();
                    }
                }
            }
        }


    }

    public function actionDownload($id)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(20 * 60);

        require('../vendor/PHPExcel/PHPExcel.php');

        $objPHPExcel = new \PHPExcel;
        
        $url = './excel/rassadka.xlsx';
        $objPHPExcel = \PHPExcel_IOFactory::load($url);
        $sheet = 0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $activeSheet = $objPHPExcel->getActiveSheet();
        $row = 2;
        $models = Saver::find()->where(['event_id'=>$id])->all();

        $models = Saver::find()
            ->select(['COUNT(*) AS cnt', 'comment'])
            ->where(['event_id'=>$id])
            ->groupBy(['comment'])
            ->all();
        $amount = 0;
        foreach ($models as $key) {
            $activeSheet->setCellValueExplicit('A'.$row, $row-1, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $activeSheet->setCellValueExplicit('B'.$row, $key->comment, \PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('C'.$row, $key->cnt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

            $places = Saver::find()
            ->where(['event_id'=>$id,'comment'=>$key->comment])
            ->all();
            $string = '';
            foreach ($places as $place) {
                if(strlen($place->place_title)>0){
                    $string.=$place->place_title.PHP_EOL;    
                }
            }
            $activeSheet->setCellValueExplicit('D'.$row, trim($string), \PHPExcel_Cell_DataType::TYPE_STRING);
            $amount+=$key->cnt;
            $row++;
        }

        $activeSheet->setCellValueExplicit('B'.$row, 'Всего', \PHPExcel_Cell_DataType::TYPE_STRING);
        $activeSheet->setCellValueExplicit('C'.$row, $amount, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,  "Excel2007");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="rassadka_'.date("Y-m-d-H-i-s").'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }

    public function actionReport($id = 1)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(10 * 60);

        require('../vendor/PHPExcel/PHPExcel.php');
        $objPHPExcel = new \PHPExcel;
        $url = './excel/report.xlsx';
        $objPHPExcel = \PHPExcel_IOFactory::load($url);
        $sheet = 0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $activeSheet = $objPHPExcel->getActiveSheet();
        $row = 2;

        $path = \Yii::getAlias('@webroot') . "/events/".$id .".json" ;

        if(!file_exists($path)) {
            return [
                "code" 		=> 1,
                "description" 	=>"File Not Found!"
            ];
        }

        $event_ids = json_decode(file_get_contents($path), true);
        $url = "https://cabinet.cultureticket.uz/api/CultureTicket/Sessions/";

        foreach($event_ids as $id) {
            $res      = $this->getResponse($url . $id);
            $sessions = json_decode($res->getBody()->getContents(), true);
            foreach($sessions["result"] as $session) {
                $counter = $this->calc($session["sessionId"]);
                $activeSheet->setCellValueExplicit('A'.$row, $session["eventName"], \PHPExcel_Cell_DataType::TYPE_STRING);
                $activeSheet->setCellValueExplicit('B'.$row, date("d.m.Y", strtotime($session["beginDate"])), \PHPExcel_Cell_DataType::TYPE_STRING);
                $activeSheet->setCellValueExplicit('C'.$row, $session["palaceName"], \PHPExcel_Cell_DataType::TYPE_STRING);
                $activeSheet->setCellValueExplicit('D'.$row, $counter["sold"], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $activeSheet->setCellValueExplicit('E'.$row, $counter["free"], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $activeSheet->setCellValueExplicit('F'.$row, $counter["sum"], \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $row++;
            }
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,  "Excel2007");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="report_'.date("Y-m-d").'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }

    public function calc($sessionId = '5614')
    {

        $url = "https://cabinet.cultureticket.uz/api/CultureTicket/SessionTickets/";
        $url1 = "https://cabinet.cultureticket.uz/api/CultureTicket/Tarifs/";

        $res = $this->getResponse($url . $sessionId);
        $res1 = $this->getResponse($url1 . $sessionId);


        $tickets = json_decode($res->getBody()->getContents(), true);
        $tarifs = json_decode($res1->getBody()->getContents(), true);

        $counter = [
            'sold' => 0,
            'free' => 0,
            'sum'  => 0,
        ];

        foreach($tickets["result"] as $ticket) {
            if($ticket["ticketStatusId"] == 3 || $ticket["ticketStatusId"] == 7) {
                if($ticket["tarifName"] == "Пригласительное место") {
                    $counter["free"]++;
                } else {
                    $counter["sold"]++;
                    foreach ($tarifs['result'] as $tarif) {
                        if ($ticket["tarifId"] == $tarif["id"]){
                            $counter["sum"] += $tarif["price"];
                        }
                    }
                }
            }
        }

        return $counter;
    }
}
