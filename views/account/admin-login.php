<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title="管理员登录";
?>
<div class="account-admin-login account_basic">
 
   <div class="main_z clearfix">
   <div class="main clearfix">
        <div class="left_box">
             <div class="img_bg"></div>
         </div>   
        <div class="right_box">
            <p class="title_p">管理员登录</p>
            <?php $form = ActiveForm::begin(['id' => 'login-form']);?>
                <?= $form->field($model, 'username')->textInput(['placeholder'=>"用户名",'maxlength'=>20]) ?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder'=>"密码",'maxlength'=>18]); ?>
                <div class="verifycode_box">
                  <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), ['captchaAction'=>'account/captcha','template' => '<div class="box">{input}<div class="image_box">{image}</div></div>','options'=>['maxlength'=>6,'class'=>'form-control','placeholder'=>'验证码']]) ?>
                </div>
              <div class="form-group loginform_rememberme clearfix">
                     <label class="control-label"></label>
                     <label class="my_rememberme_label">
                     	<input class="xuanzhe_box"  type="checkbox" id="loginform-rememberme"  checked="checked">
                  		    记住密码
                  </label>
				    <p class="forget_pswd"><a class="color_blue"  href="<?=Url::toRoute('account/forget-adminpwd-email'); ?>">忘记密码？</a></p>
				</div>
                <div class="w_group_submit">
                    <?= Html::submitButton('登录', ['class' => 'btn my_submit', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
         </div>   
 
    </div>
  </div>

</div>

 <script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
//////////////////////
$(document).ready(function(){


})
////////////////////////
    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>