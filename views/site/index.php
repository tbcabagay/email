<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */

$this->title = 'UP Open University';
?>
<div class="site-index">

    <div class="body-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'recipients')->textArea(['rows' => 3]) ?>

        <?= $form->field($model, 'subject')->textInput() ?>

        <?= $form->field($model, 'body')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'full'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
