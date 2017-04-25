<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
$this->title = '意见建议';
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

<div class="site-suggestion">

			<div class="box_bd clearfix">
               <div class="uc_avatar">  
                   <a href="<?=Url::toRoute(['site/index']); ?>"><img class="show_cover" src="<?= Yii::$app->homeUrl.'images/'.(Yii::$app->user->identity->headimg?Yii::$app->user->identity->headimg:"basic/basic_header.jpg") ?>"  /></a>
                </div>
                <div class="uc_info">
                    <h3 class="uc_welcome"><span class="user_name"><?= Yii::$app->user->identity->username; ?>
                    </span>
                    <?php
						$h=date('G');
						if ($h<11) echo '早上好~';
						else if ($h<13) echo '中午好~';
						else if ($h<17) echo '下午好~';
						else echo '晚上好~';
					?>
                    </h3>
                </div>
                </div>
	
    <div class="account-form form_basic">

	    <?php $form = ActiveForm::begin(); ?>
	    
	    <?= $form->field($model, 'content')->textarea(['maxlength' => 1000]) ?>
	
	    <div class="form-group submit_group">
	        <?= Html::submitButton('发送意见建议',['class' => 'btn btn-success']) ?>
	    </div>
	
	    <?php ActiveForm::end(); ?>

	</div>

</div>
