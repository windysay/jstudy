<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title="講師登録";
?>
<div class="account-teacher-login account_basic">
 
   <div class="main_z clearfix">
   <div class="main clearfix">
        <div class="left_box">
             <div class="img_bg"></div>
         </div>   
        <div class="right_box">
            <p class="title_p">講師登録</p>
            <?php $form = ActiveForm::begin(['id' => 'login-form']);?>
                <?= $form->field($model, 'username')->textInput(['placeholder'=>"電子メール",'maxlength'=>20]) ?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder'=>"暗号",'maxlength'=>18]); ?>
                <div class="verifycode_box">
                  <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), ['captchaAction'=>'account/captcha','template' => '<div class="box">{input}<div class="image_box">{image}</div></div>','options'=>['maxlength'=>6,'class'=>'form-control','placeholder'=>'検証コード']]) ?>
                </div>
                <div class="form-group loginform_rememberme clearfix">
                     <label class="control-label"></label>
                     <label class="my_rememberme_label">
                     	<input class="xuanzhe_box"  type="checkbox" id="loginform-rememberme"  checked="checked">
                         保持登録
                  </label>
                    <p class="forget_pswd"><a class="color_blue"
                                              href="<?= Url::toRoute('account/forget-password-email'); ?>">パスワードを忘れた？</a>
                    </p>
				</div>
                <div class="w_group_submit">
                    <?= Html::submitButton('登録', ['class' => 'btn my_submit', 'name' => 'login-button']) ?>
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