<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
 
$this->title = '学生注册';
$this->registerJsFile(Yii::$app->homeUrl.'js/validate.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'js/jquery.cookie.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
  
<div class="account-register account_basic">

	<div class="main_z clearfix">
  	  <div class="main clearfix">
        <div class="left_box">
             <div class="img_bg"></div>
         </div> 
  		 <div class="right_box">
			   <p class="title_p">学生注册</p>
			<?php $form = ActiveForm::begin(); ?>
		    <?= $form->field($model, 'mobile',['enableAjaxValidation' => true])->textInput(['maxlength' =>11,'placeholder'=>"输入手机号码"]) ?>
			<div class="form-group field-student-phoneCode required">
				<div class="input-group">
				   <input type="text" id="student-phoneCode" class="form-control" name="Student[phoneCode]" maxlength="6"  placeholder="手机验证码">
	               <div class="input-group-addon send_code clearfix">
	                   <div class="sms_code pull-left">短信验证</div>
	                   <div class="pull-left line"></div>
	                   <div class="voice_code pull-left">语音验证</div>
	               </div>
	            </div>
				<div class="help-block one_f_warn"></div>
			</div> 
			 <?= $form->field($model, 'email',['enableAjaxValidation' => true])->textInput(['maxlength' =>40,'placeholder'=>"接收消息的电子邮箱"]) ?>
		    <?= $form->field($model, 'password')->passwordInput(['maxlength' =>18,'placeholder'=>"登录密码"]) ?>
		    <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' =>18,'placeholder'=>"确认登录密码"]) ?>
	      <div class="form-group submit_group">
		        <?= Html::submitButton('立即注册', ['class'=>'btn btn-success my_btn']) ?>
		    </div>
			<?php // echo $form->errorSummary( $model ) ?>
		    <?php ActiveForm::end(); ?>
		</div>
	</div>
     </div>
	
</div><!-- student-register -->

 <script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
     var code_countdown=90;
	 var send_status=true;
	 var daojishi_time=$.cookie('code_countdown_time');
	 var code_type= $.cookie('code_type');  

     function set_code_countdown(){
       var code_type= $.cookie('code_type');  
       if(send_status==true){
             return false;
       }
       if(code_type=='1'){
    	   $(".send_code .sms_code").text(code_countdown+'秒');  
       }else{
    	   $(".send_code .voice_code").text(code_countdown+'秒');  
       }
      
       $.cookie('code_countdown_time',code_countdown,{ path: '/', expires:1});
       code_countdown--;
       if(code_countdown==-1){
    	   code_countdown=90;
    	   $(".send_code .sms_code").text('短信验证');
    	   $(".send_code .voice_code").text('语音验证');
    	   $(".send_code .sms_code").removeClass("disabled");
    	   $(".send_code .voice_code").removeClass("disabled");
    	   send_status=true;
    	  // deleteMyCookie('code_countdown_time')
    	   $.cookie('code_countdown_time','',{ path: '/'});
    	   $.cookie('code_type','',{ path: '/'});
       }else{
           setTimeout(function(){set_code_countdown()},1000);
       }
      }
 
//////////////////////
$(document).ready(function(){
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
	
	
//验证验证码的正确性
   $("#student-phoneCode").blur(function(){
	     var code=$.trim($(this).val());
	     var phone=$.trim($("#student-mobile").val());
		  var guizhe=/^[0-9]{6}$/; 
		  if(!code){
			  $(".one_f_warn").text('请输入验证码')
			   return false;
		  }if(!guizhe.test(code)) {//如果验证不通过
               $(".one_f_warn").text('验证码格式错误')
			   return false;
		  }else{
		       $.ajax({
		 	 	   url:"<?=Url::toRoute(['smscode/ajax-validate-code']); ?>",
		           type:'POST',
		           data:{'code':code,'phone':phone,'use_type':1},
		           dataType: 'json',
		           timeout: 15000,
		           error: function(){
		               warn('系统繁忙，稍后再试',0);
		          },
		           success: function(data){
		        	   if(data=='success'){
		     			  $(".one_f_warn").text('')
		                  $(".one_f_warn").removeClass('one_f_warn')
			            }else if(data=='code_overdue'){
			  			  $(".one_f_warn").text('验证码已过期，请重新获取')
					    }else if(data=='no_code'){
				  			  $(".one_f_warn").text('验证码错误')
					    }
		          }
		       }); 
	      }
    })
    
    $(".my_btn").click(function(){
       var tool=$("body").find(".one_f_warn")
       if(tool.length>0){
           return false	
       }
    })
    
 
////////////////////////
})

	function ajax_send_code(code_type,text,obj){
        var phone=$("#student-mobile").val();
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
            data:{'code_type':code_type,'phone':phone,'use_type':1},
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
             	   warn('您操作次数过多，明日再试吧',0);
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