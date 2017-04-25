<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\UploadImages64;
$this->title="重设密码";
?>
<div class="account-teacher-login account_basic">
 
   <div class="main_z clearfix">
   <div class="main clearfix">
        <div class="left_box">
             <div class="img_bg"></div>
         </div>   
        <div class="right_box">
            <p class="title_p">重置密码</p>
             <?php $form = ActiveForm::begin(['id'=>'login-form']); ?>
				<?= $form->field($member, 'newPassword',['inputOptions'=>['class'=>'form-control login_input']])->passwordInput(['maxlength' =>18,'placeholder'=>"请输入6-18位新密码" ]) ?>
	    		<?= $form->field($member, 'confirmNewPassword',['inputOptions'=>['class'=>'form-control login_input']])->passwordInput(['maxlength' =>18,'placeholder'=>"请重新输入新密码"]) ?>
                <div class="w_group_submit">
                    <?= Html::submitButton('确定重置', ['class' => 'btn btn-success my_btn my_submit' , 'name' => 'login-button']) ?>
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
