<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'email')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'password')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'role')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => 'Выбор роли ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    ?>


    <div class="form-group">
        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
