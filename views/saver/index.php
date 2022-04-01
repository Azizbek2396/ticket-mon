<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SaverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('custom', 'Рассадка');
$this->params['breadcrumbs'][] = $this->title;
//var_dump(Yii::$app->request->getQueryParam('page'));die();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a(Yii::t('custom', 'Создать'), ['site/scheme'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                    <input type="hidden" name="page" value="<?= Yii::$app->request->getQueryParam('page') ?>">

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
//                            ['class' => 'yii\grid\SerialColumn'],

                            'id',
                            'event_id',
                            'seat_id',
                            'comment',
                            'place_title',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'buttons'  => [

                                    'delete' => function($url, $model) {
	                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                                ['delete', 'id' => $model['id'], 'page' => (Yii::$app->request->getQueryParam('page')) ? Yii::$app->request->getQueryParam('page') : '1'],
                                                [
                                                        'title' => 'Удалить', 'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?','data-method' => 'post'
                                                ]
                                            );
                                        }

                                    ]
                            ],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                            'firstPageLabel' => 'First',
                            'lastPageLabel' => 'Last',
                        ]
                    ]); ?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
