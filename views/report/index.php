<?php
/* @var $this yii\web\View */

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

<div class="row pt-5 pb-4">
    <div class="col-3">
        <?php
        echo DatePicker::widget([
            'name' => 'from_date',
            'value' => date('d.m.Y', strtotime('-1 months')),
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'to_date',
            'value2' => date('d.m.Y'),
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);
        ?>
    </div>
    <div class="col-3">
        <?php
            echo Select2::widget([
                'name' => 'types',
                'id' => 'types',
                'attribute' => 'types',
                'data' => $types,
                'options' => [
                    'placeholder' => 'Выберите тип',
//                    'multiple' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    "select2:select" => "function() { 
                                $.post( '?r=report/get-organisations&id='+$(this).val(), function( data ) {
                                  $( 'select#orgs' ).html( data );
                                });
                         
                         }",
                ]
            ]);
        ?>
    </div>
    <div class="col-6">
        <?php
        echo Select2::widget([
            'name' => 'orgs',
            'id' => 'orgs',
            'attribute' => 'orgs',
            'data' => [],
            'options' => [
                'placeholder' => 'Выберите оргонизации',
                    'multiple' => true
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'selectOnClose' => false,
            ],
        ]);
        ?>
    </div>
</div>

<div class="my-2 form-group">
    <?= Html::submitButton('Скачать Excel', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
