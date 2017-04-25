<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
$this->title = '我的资料';
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

<p class="f_top_title clearfix">
	     <?= $this->title ?>
	 	 <span class="r_box info_type_a pull-right">
    	  	 <a class="w_link_a _index_a"  href="<?=Url::toRoute(['info']); ?>">基本信息</a>
    	  	 <span class="ge">|</span>
    	  	 <a class="w_link_a"  href="<?=Url::toRoute(['headimg']); ?>">头像信息</a>
	  	 </span>
	 </p>
	 
<div class="site-info">
	
    <div class="account-form form_basic">

	    <?php $form = ActiveForm::begin(); ?>
	    <?= $form->field($model, 'realname')->textInput(['maxlength' => 30]) ?>
        <?= $form->field($model, 'sex')->RadioList(['1'=>'男','0'=>'女']) ?>
          <?= $form->field($model, 'skype')->textInput(['maxlength' => 30]) ?>
	    <?= $form->field($model, 'qq')->textInput(['maxlength' => 10]) ?>
	    <?= $form->field($model, 'wechat')->textInput(['maxlength' => 20]) ?>
	  <?= $form->field($model, 'address')->textArea(['maxlength' => 50]) ?>
	
	    <div class="form-group submit_group">
	        <?= Html::submitButton('保存资料',['class' => 'btn btn-success']) ?>
	    </div>
	
	    <?php ActiveForm::end(); ?>

	</div>

</div>
