<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
$this->title = '设置用户名';
?>
<script>
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
	$(document).ready(function(){
		
 
	})//////
 
<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	

<p class="f_top_title clearfix"><?= $this->title ?></p>
	 
<div class="site-info">
	
    <div class="account-form form_basic">

	    <?php $form = ActiveForm::begin(); ?>
	    <div class="form-group">
			<label class="control-label" for="student-username">注：</label>
			<span style="height:35px;line-height:35px; margin-left: 30px;">用户名设置以后，不可修改</span>
		</div>
	    <?= $form->field($model, 'username')->textInput(['maxlength' => 18,'placeholder'=>'6-18位字母或数字']) ?>
	
	    <div class="form-group submit_group">
	        <?= Html::submitButton('保存',['class' => 'btn btn-success']) ?>
	    </div>
	
	    <?php ActiveForm::end(); ?>

	</div>

</div>
