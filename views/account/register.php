<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
 
$this->title = '会员注册';
$this->registerJsFile(Yii::$app->homeUrl.'js/validate.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'js/jquery.cookie.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
  
<div class="account-register2 account_basic">

	<div class="main_z clearfix">
  	  <div class="main clearfix">
         <p class="t1">会员注册</p>
  		 <div class="remain_box">

		  	<?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username',['enableAjaxValidation' => true])->textInput(['maxlength' =>18,'placeholder'=>"※提交后无法修改"]) ?>
			<?= $form->field($model, 'email',['enableAjaxValidation' => true])->textInput(['maxlength' =>40,'placeholder'=>"接收消息的电子邮箱"]) ?>
		    <?= $form->field($model, 'password')->passwordInput(['maxlength' =>18,'placeholder'=>"登录密码"]) ?>
		    <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' =>18,'placeholder'=>"确认登录密码"]) ?>
	        <?= $form->field($model, 'qq')->textInput(['maxlength' =>true,'placeholder'=>"您的qq号"]) ?>
	        <?= $form->field($model, 'skype')->textInput(['maxlength' =>true,'placeholder'=>"您的skype账号"]) ?>
	        <div class="help-block" style="margin:-20px 0 0 50px;">（※以上两项请选填一项）</div>
	        <?= $form->field($model, 'mobile',['enableAjaxValidation' => true])->textInput(['maxlength' =>11,'placeholder'=>"输入手机号码"]) ?>
	        <div class="help-block" style="margin:-20px 0 0 50px;">（不填写也可以正常注册哦，使用试听券时需要手机加验证码，一个账户只能验证一次）</div>
	        <?= $form->field($model, 'chengdu')->dropDownList($chengdu) ?>
            <?= $form->field($model, 'xueximudi')->dropDownList($xueximudi) ?>
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
 
function showChenduHtml(on){
  if(on==1){
     var html='<div class="form-group f_chengdu2"><label class="control-label" for="student-chengdu">日语程度</label><input type="text" id="student-chengdu" class="form-control" name="Student[chengdu]" maxlength="500" placeholder="请填写您的日语程度"></div>';
     $(".field-student-chengdu").after(html);
    
  }else{
   $(".f_chengdu2").remove();
  }
}
  
function showXueximudiHtml(on){
  if(on==1){
     var html='<div class="form-group f_xueximudi2"><label class="control-label" for="student-xueximudi">学习日语的目的</label><input type="text" id="student-xueximudi" class="form-control" name="Student[xueximudi]" maxlength="500" placeholder="请填写您学习日语的目的"></div>';
     $(".field-student-xueximudi").after(html);
    
  }else{
   $(".f_xueximudi2").remove();
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
	
	
 $(".my_btn").click(function(){
   var qq=$("#student-qq").val();
   var skype=$("#student-skype").val();
   if(qq||skype)
     return true;
   else{
      alert('请填写QQ或者skype,二选一');
      return false;
   }
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
    
    $("#student-chengdu").change(function(){
      var id=$(this).val()
      if(id==0){
        showChenduHtml(1);
        $(".f_chengdu2").fadeIn(400);
      }else{
        $(".f_chengdu2").fadeOut(400);
        showChenduHtml(0);
      }
    })
    
    $("#student-xueximudi").change(function(){
      var id=$(this).val()
      if(id==0){
        showXueximudiHtml(1);
        $(".f_xueximudi2").fadeIn(400);
      }else{
        $(".f_xueximudi2").fadeOut(400);
        showXueximudiHtml(0);
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