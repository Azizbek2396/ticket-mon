<?php
namespace app\controllers;

use app\models\Saver;
use Yii;
use yii\rest\Controller;

class SeatController extends Controller
{
    public function beforeAction($action)
    {
        $parent = parent::beforeAction($action);
        Yii::$app->response->format = "json";
        return $parent;
    }

    public function actionIndex()
    {
        $models = Saver::find()->where(['event_id'=> 3])->all();

        $zakrep_arr = [];
        $accessable_count = 0;
        foreach ($models as $key) {
            $colorclass = substr(hash('ripemd160', $key->comment), -6);
            $zakrep_arr[$key->seat_id] = [
                'color' => $colorclass,
                'comment' => $key->comment,
                'place_title' => $key->place_title
            ];

        }

//        $counts = [];
//        $seatCount = 0;
//        $comment = $zakrep_arr[0]['comment'];
//
//        foreach ($zakrep_arr as $seat) {
//            if ($seat)
//        }

        return [
            'count' => $accessable_count,
            'seats'  => $zakrep_arr,
        ];
    }


    public function actionUpdate()
    {
//        $data = Yii::$app->request->post();
//        if(isset($data['seats'])) {
//           foreach ($data['seats'] as $seat) {
//               $model = Saver::find()
//                   ->where(['seat_id' => $seat['id']])
//                   ->one();
//
//               if(empty($model)) {
//                   $model = new Saver();
////                   $model->place_title = $seat['place_title'];
//               }
//               $model->comment = $data['comment'];
//               $model-empty>save(false);
//            }
//        }
//        return $data;
    }
}