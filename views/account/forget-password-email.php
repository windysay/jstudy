<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title="忘记密码";
?>
<div class="account-teacher-login account_basic">
 
   <div class="main_z clearfix">
   <div class="main clearfix">
        <div class="left_box">
             <div class="img_bg"></div>
         </div>   
        <div class="right_box">
            <p class="title_p">忘记密码</p>
            <p class="" style="text-align: center;margin-bottom:30px;color:#777;">如果提示发送成功却在邮箱没找到邮件，<br>请在邮箱->垃圾箱中查找</p>
            <div id="login-form">
            	  <input class="form-group form-control" id="email" placeholder="输入绑定邮箱" maxlength="20"/>
                <div class="w_group_submit">
                    <button type="submit" class="btn my_submit" >发送邮件</button>
                </div>
             </div>
         </div>   
 
    </div>
  </div>

</div>

<script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
//////////////////////
function CheckMail(mail) {
	 var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	 if (filter.test(mail)) return true;
	 else {
	 alert('您的电子邮件格式不正确');
	 return false;}
	}
$(document).ready(function(){
	$(".my_submit").click(function(){
			var email=$("#email").val();
			if(CheckMail(email)==false) return false;
			warn("邮件发送中 · · ·",1);
			$.ajax({
			 	  url:"<?=Url::toRoute('ajax-send-password-email'); ?>",
		           type:'POST',
		           data:{'email':email},
		           dataType: 'json',
		           timeout: 15000,
			       success: function(data){
			        	    if(data==1){
			        	    	alert('发送成功！若找不到邮件，请在邮箱->垃圾箱中查找');
			        	    	window.location.href="<?=Url::toRoute("teacher-login")?>";
							}else{
								warn('该邮箱还没注册，请重新输入',0);
		                	}
			        },
		           error: function(data){
			           warn('系统繁忙，请稍后再试',0);
		       	  },
			      }); 
		})

})
////////////////////////
<?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>