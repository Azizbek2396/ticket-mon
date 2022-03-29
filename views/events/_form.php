<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
  $bool = [
          0 => 'False',
          1 => 'True'
  ];
?>

<div class="events-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'hall')->textInput() ?>

    <?= $form->field($model, 'event_id')->textInput() ?>

    <?= $form->field($model, 'session_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'is_active')->dropDownList($bool) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
