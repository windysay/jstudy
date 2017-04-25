<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$title='更换手机号码';
$this->title = 'IPERAPERA-'.$title;
$this->registerJsFile(Yii::$app->homeUrl.'js/jquery.cookie.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'js/validate.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>

<div class="member-change-mobile form_basic phoneCode_basic">
	 <p class="f_top_title"><?= $title ?></p>

        <p class="top_t1">绑定新手机号码前，先验证您绑定中的号码</p>
        
        <?php $form = ActiveForm::begin(); ?>
        
	    <div class="form-group field-member-mobile clearfix">
            <label class="control-label" for="member-mobile">手机号码</label>
            <p id="member-mobile">您当前绑定手机号码: <span> <?= $model->mobile ?></span></p>
            
            <div class="help-block"></div>
        </div>
	
	    <?= $form->field($model, 'phoneCode',['enableAjaxValidation' => true])->textInput(['maxlength' => 6]) ?>
	    
        <div class="form-group clearfix">
                <label class="control-label  pull-left "></label>
                <div class="send_code pull-left clearfix">
                   <span class="sms_code btn btn-default pull-left">短信验证码</span>
                   <span class="voice_code btn btn-default  pull-left">语音验证码</span>
                </div>
       </div>
        
         <div class="form-group submit_group">
	        <?= Html::submitButton('下一步', ['class'=>'btn btn-success my_btn']) ?>
	    
	    </div>
	     
	     <?php ActiveForm::end(); ?>
 
	   
</div>
<script>
  <?php $this->beginBlock('MY_VIEW_JS_END') ?>
		var use_type="<?= $model->phoneCodeUseType ?>";
		var phone="<?= $model->mobile ?>";
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
	      
	       $.cookie('code_countdown_time',code_countdown,{ path: '/'});
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

	    
	    $(".my_btn").click(function(){
	       var tool=$("body").find(".one_f_warn")
	      if(tool.length>0)
	           return false	
	    })
	    
	 
	////////////////////////
	})

		function ajax_send_code(code_type,text,obj){
	      
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
	            data:{'code_type':code_type,'phone':phone,'use_type':use_type},
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
	                    warn('您的手机号码有误',0);
	             	   $(".send_code .sms_code").removeClass("disabled");
	             	   $(".send_code .voice_code").removeClass("disabled");
	              	   obj.text(text);
	             	   send_status=true;
	                }else if(data=='no_register'){
	                    warn('此手机号码未被绑定',0);
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
