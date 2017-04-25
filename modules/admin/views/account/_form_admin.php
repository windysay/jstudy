<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="account-index">
    <div class="f_top_title">修改用户名/邮箱</div>
    <div class="xm_box clearfix">
<div class="teacher-form form_basic">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => 40]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 40]) ?>
    
    <div class="submit_group form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<script>
<?php $this->beginBlock('MY_VIEW_JS_END') ?>

<?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
