<?php
namespace app\controllers\rbac;

use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;

class  BaseController extends Controller
{

    public $test = "a";

    public function beforeAction($action)
    {
        $parent = parent::beforeAction($action);
        $this->test = "b";
        Yii::$app->response->format = "json";
        if($id == "1") {
            throw new ForbiddenHttpException("Deny");
        }
        return $parent;
    }

    public function actionIndex()
    {
        return [
            'ControllerId' => $this->id,
            'Action' => $this->action->id,
            'test'  => $this->test,
        ];
    }


    public function actionTest()
    {
        return [
            'ControllerId' => $this->id,
            'Action' => $this->action->id,
        ];
    }
}