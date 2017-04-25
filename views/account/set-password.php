<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
$this->title='Iperapera-找回密码';
$this->registerJsFile(Yii::$app->homeUrl.'js/validate.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'js/jquery.cookie.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="account-set-password">
  <div class="set_password_main clearfix">
    <div class="form_main">
    
     <p class="t1">设置您的新密码</p>
        
        <div class="form_basic">
        <div class="form-group field-mobile required my_form_box">
			<label class="control-label" for="mobile">手机号码</label>
			<input type="text" id="mobile" class="form-control "  maxlength="11" disabled="disabled" value="<?= isset($cookie_phone)?$cookie_phone:null; ?>">
		</div>
		<div class="form-group field-phoneCode required my_form_box">
			<label class="control-label" for="phoneCode">短信效验码</label>
			<div class="input-group">
			   <input type="text" id="phoneCode" class="form-control"   maxlength="6">
               <div class="input-group-addon send_code clearfix">
                   <div class="sms_code pull-left">短信验证</div>
                   <div class="pull-left line"></div>
                   <div class="voice_code pull-left">语音验证</div>
               </div>
            </div>
		</div> 
        <div class="form-group field-newPassword required my_form_box">
			<label class="control-label" for="newPassword">新密码</label>
			<input type="password" id="newPassword" class="form-control "  maxlength="18"  placeholder="输入您的新密码">
		</div>
        <div class="form-group field-confirmNewPassword required my_form_box">
			<label class="control-label" for="confirmNewPassword">确认新密码</label>
			<input type="password" id="confirmNewPassword" class="form-control "  maxlength="18"   placeholder="确认您的新密码">
		</div>
          <div class="form-group submit_group">
                    <?= Html::submitButton('确认提交', ['class' => 'btn btn-success my_submit',]) ?>
          </div>
 
 </div>
 
 
    </div>
  </div>
</div>

 <script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
 
    var code_countdown=90;
	var send_status=true;
    var btn_status=true;
    var daojishi_time=$.cookie('code_countdown_time');
	 var code_type= $.cookie('code_type'); 
	
     function set_code_countdown(){
       if(send_status==true){
             return false;
       }
       var code_type= $.cookie('code_type');
       if(code_type=='1'){
    	   $(".send_code .sms_code").text(code_countdown+'秒');  
       }else{
    	   $(".send_code .voice_code").text(code_countdown+'秒');  
       }
       setMyCookie('code_countdown_time',code_countdown,1)
       code_countdown--
       if(code_countdown==-1){
    	   code_countdown=90
    	   $(".send_code .sms_code").text('短信验证');
    	   $(".send_code .voice_code").text('语音验证');
    	   $(".send_code .sms_code").removeClass("disabled");
    	   $(".send_code .voice_code").removeClass("disabled");
    	   send_status=true;
    	   deleteMyCookie('code_countdown_time');
    	   deleteMyCookie('code_type');
       }else{
           setTimeout(function(){set_code_countdown()},1000)
       }
      }
       
//////////////////////
$(document).ready(function(){
 
	$("#header_z").animate({
	    height:'55px',
	})
	$("#header_z").attr("class","header_z2")
	$("#header_z").attr("id","")
	
	var cookie_code_countdown_time= $.cookie('code_countdown_time'); 
    
    if(cookie_code_countdown_time>0){//检测倒计时 cookie
       send_status=false;
       $(".send_code .voice_code").addClass("disabled");
       $(".send_code .sms_code").addClass("disabled");
       code_countdown=daojishi_time;
       set_code_countdown();
    }
	
	$(".send_code .sms_code").click(function(){//发送验证码
		  var code_type=1;
		  var text=$(this).text();
		  var obj=$(this);
		  ajax_send_code(code_type,text,obj);
    })
	$(".send_code .voice_code").click(function(){//发送验证码
		  var code_type=2;
		  var text=$(this).text();
		  var obj=$(this);
		  ajax_send_code(code_type,text,obj);
    })
 

   $("#phoneCode").blur(function(){
	     var code=$.trim($(this).val());
	     var phone=$.trim($("#mobile").val());
		  var guizhe=/^[0-9]{6}$/;
		  if(!code){
			  $(".field-phoneCode").find('.my_f_warn').remove();
			  $(".field-phoneCode").append('<p class="my_f_warn">请输入验证码</p>');
		  }else if(!guizhe.test(code)) {//如果验证不通过
			   $(".field-phoneCode").find('.my_f_warn').remove();
			   $(".field-phoneCode").append('<p class="my_f_warn">验证码格式错误</p>');
		  }else{
			   $(".field-phoneCode").find('.my_f_warn').remove();
	     }
    })
 
 
 $("#newPassword").blur(function(){
     var validate=new Validate()
     var is_password=validate.formVPassword('newPassword');
     if(is_password=='_no_') {
       	  return false;
      }
 })
 
 $("#confirmNewPassword").blur(function(){
       var newPassword=$("#newPassword").val();
       var confirmNewPassword=$(this).val();
       if(newPassword!=confirmNewPassword){
			  $("#confirmNewPassword").siblings(".my_f_warn").remove();
			  $("#confirmNewPassword").parent().addClass("has-error")
              $("#confirmNewPassword").after('<p class="my_f_warn">两次密码输入不一样</p>')
       }else{
			  $("#confirmNewPassword").parent().removeClass("has-error")
	          $("#confirmNewPassword").siblings(".my_f_warn").remove();
       }
 })
    

    $(".my_submit").click(function(){
       var tool=$("body").find(".my_f_warn")
       if(tool.length>0)
           return false	
       if(btn_status==false)
           return false;
       btn_status=false;
       var newPassword=$("#newPassword").val();
       var confirmNewPassword=$("#confirmNewPassword").val();
       var code=$("#phoneCode").val();
       var phone=$("#mobile").val();
       
       if(newPassword&&confirmNewPassword&&code&&phone){
       	$.ajax({
	 	  url:"<?=Url::toRoute('ajax-set-newpassword'); ?>",
           type:'POST',
           data:{'code':code,'phone':phone,'newPassword':newPassword,'confirmNewPassword':confirmNewPassword,"use_type":2},
           dataType: 'json',
           timeout: 15000,
           error: function(){
	               warn('系统繁忙，稍后再试',0);
	               btn_status=true;
	       },
	       success: function(data){
	        	    if(data=='success'){
	        	    	deleteMyCookie('code_countdown_time');
	        	    	deleteMyCookie('code_type'); 
  				    	var url="<?=Url::toRoute('account/login'); ?>";
	  			        warnRedirect('恭喜，新密码设置成功',1,url);
					}else if(data=='error_code'){
	  		        	$(".field-phoneCode").find('.my_f_warn').remove();
	                 	$(".field-phoneCode").append('<p class="my_f_warn">验证码错误</p>');
	                 	 btn_status=true;
				    }else if(data=='fail'){
		               warn('保存密码失败',0);
		               btn_status=true;
                	}
	        }
	      }); 
       }else{
          return false;
       }
    })
	
 
////////////////////////
})


		function ajax_send_code(code_type,text,obj){
        var phone=$("#mobile").val();
        if(send_status==false){
             return false;
        }
        var validate=new Validate();
        var mobile=validate.VMobile(phone);
        if(!mobile) {
             warn('请输入正确的手机号码',0);
         	return false;
        }
    	$.cookie('code_countdown_time',90,{path: '/'}); 
    	$.cookie('code_type',code_type,{path: '/'}); 
        send_status=false;
        $(".send_code .sms_code").addClass("disabled");
        $(".send_code .voice_code").addClass("disabled");
        obj.text('正在发送');
        
        $.ajax({
    	 	   url:"<?=Url::toRoute(['/smscode/ajax-send-code']); ?>",
            type:'POST',
            data:{'code_type':code_type,'phone':phone,'use_type':2},
            dataType: 'json',
            timeout: 15000,
            error: function(){
         	   warn('系统繁忙，稍后再试',0);
         	   $(".send_code .sms_code").removeClass("disabled");
         	   $(".send_code .voice_code").removeClass("disabled");
          	   obj.text(text);
         	   send_status=true;
           },
            success: function(data){
                if(data=='success'){//成功
             	   set_code_countdown();
                }else if(data=='error_phone'){
                    warn('请输入正确的手机号码',0);
             	   $(".send_code .sms_code").removeClass("disabled");
             	   $(".send_code .voice_code").removeClass("disabled");
              	   obj.text(text);
             	   send_status=true;
                }else if(data=='phone_used'){
                    warn('此手机号码已被注册',0);
             	   $(".send_code .sms_code").removeClass("disabled");
             	   $(".send_code .voice_code").removeClass("disabled");
              	   obj.text(text);
             	   send_status=true;
                }else if(data=='times_out'){
             	   warn('您操作次数过多，明天再试吧',0);
             	   $(".send_code .sms_code").removeClass("disabled");
             	   $(".send_code .voice_code").removeClass("disabled");
              	   obj.text(text);
             	   send_status=true;
                }else if(data=='fail'){
             	   warn('发送失败',0);
             	   $(".send_code .sms_code").removeClass("disabled");
             	   $(".send_code .voice_code").removeClass("disabled");
              	   obj.text(text);
             	   send_status=true;
                }
          	 }
        }); 
    
    }


    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>