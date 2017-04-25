<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title=Yii::$app->id;
$this->registerJsFile(Yii::$app->homeUrl.'js/validate.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="account-forget-password">
  <div class="forget_password_main clearfix">
     <p class="t1">找回密码中</p>
    
    <div class="forget_main form_basic">
         <?php $form = ActiveForm::begin();?>
         <?= $form->field($model, 'phone',['enableAjaxValidation' => true])->textInput(['class'=>'form-control','placeholder'=>"填写您的手机号码",'maxlength'=>'11']) ?>
         <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), ['template' => '<div class="box">{input}<div class="image_box">{image}</div></div>','options'=>['maxlength'=>6,'class'=>'form-control'],'imageOptions'=>['id'=>'find_password_img']]) ?>
 
         <div class="form-group submit_group">
                    <?= Html::submitButton('下一步', ['class' => 'btn btn-success my_submit', 'name' => 'login-button']) ?>
          </div>
       <?php ActiveForm::end(); ?>
 
  </div>
 
  </div>
 
</div>

 <script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
//////////////////////
$(document).ready(function(){
 
	$("#header_z").animate({
	    height:'55px',
	})
	$("#header_z").attr("class","header_z2")
	$("#header_z").attr("id","")
 
 
    $(".my_submit").click(function(){
       var tool=$("body").find(".has-error")
      if(tool.length>0)
           return false	
    })
 
 
////////////////////////
})
    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>