<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline item'],
        'fieldConfig' => ['options' => ['class' => 'form-group form-group-b']],
    ]); ?>

    <span>Mostrar ultimos:</span>
    <?= $form->field($model, 'timeNumber')->textInput(['size'=>'4', 'tag'=>false, 'class' => 'form-control form-control-inline input-sm', 'onblur' => 'this.form.submit()'])->label(false); ?>
    <?= $form->field($model, 'timeUnit')->dropDownList($allowedTimeUnits, ['class' => 'selectpicker', 'data-style' => 'btn btn-default btn-sm', 'data-width' => 'fit', 'onchange' => 'this.form.submit()'])->label(false); ?>

    <?php ActiveForm::end(); ?>

</div>