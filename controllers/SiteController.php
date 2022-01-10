<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
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
    public const EVENTID = 2;
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


    public function actionScheme()
    {

        $models = Saver::find()->where(['event_id'=>$this::EVENTID])->all();

        return $this->render('scheme', [
            'models' => $models,
        ]);
    }

    public function actionSaver()
    {
        foreach (Yii::$app->request->post('seats') as $key => $value) {
            $model = new Saver();
            $model->event_id = $this::EVENTID;
            $model->seat_id = $value['seatid'];
            $model->place_title = $value['title'];
            $model->comment = Yii::$app->request->post('comment');
            if(Saver::find()->where(['event_id'=>$model->event_id,'seat_id'=>$model->seat_id])->one()){
                $a = 1;
            }
            else{
                $model->save();
            }
            
        }
        die;
    }

    public function actionDownload()
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
        $models = Saver::find()->where(['event_id'=>$this::EVENTID])->all();

        $models = Saver::find()
            ->select(['COUNT(*) AS cnt', 'comment'])
            ->where(['event_id'=>$this::EVENTID])
            ->groupBy(['comment'])
            ->all();
        $amount = 0;
        foreach ($models as $key) {
            $activeSheet->setCellValueExplicit('A'.$row, $row-1, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $activeSheet->setCellValueExplicit('B'.$row, $key->comment, \PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->setCellValueExplicit('C'.$row, $key->cnt, \PHPExcel_Cell_DataType::TYPE_NUMERIC);

            $places = Saver::find()
            ->where(['event_id'=>$this::EVENTID,'comment'=>$key->comment])
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
}
