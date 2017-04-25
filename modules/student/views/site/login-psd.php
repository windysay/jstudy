<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '修改密码';
?>
<script type="text/javascript">
	 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
	   $(document).ready(function(){
		   
				
		})
			 
	<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	

<div class="account-login-psd">
	 <p class="f_top_title"><?= Html::encode($this->title) ?></p>
    <div class="member-form form_basic">

	    <?php $form = ActiveForm::begin(); ?>
	
	    <?= $form->field($model, 'oldPassword',['enableAjaxValidation' => true])->passwordInput(['maxlength' => 18]) ?>
	
	    <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => 18]) ?>
	
	    <?= $form->field($model, 'confirmNewPassword')->passwordInput(['maxlength' => 18]) ?>
	 
	    <div class="form-group submit_group">
	        <?= Html::submitButton('保存',['class' => 'btn btn-success']) ?>
	    </div>
	
	    <?php ActiveForm::end(); ?>

	</div>

</div>
