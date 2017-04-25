<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title='账户安全';
?>
<script>
	 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
	 
			 $(document).ready(function(){

					var success_message="<?=Yii::$app->session->getFlash('success') ?>";
					if(success_message){
						warn(success_message,1);
					}
			 })
			 
 
	<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	

<div class="site-safe">
	 <p class="f_top_title"><?= $this->title ?></p>
     <div class="user_safe_main">
	  <ul class="safe_list">
	  	  <li>
    	      <div class="title box">
    	          <p class="img_p">
        	       <?php if($model->username):?>
        	      <span class="glyphicon glyphicon-ok"></span>
        	      <?php else:?>
        	      <span class="glyphicon glyphicon-remove"></span>
        	      <?php endif;?>
        	      </p>
    	           <h3 class="t3">用户名</h3>
    	      </div>
    	      <?php if($model->username):?>
    	      <div class="info box">您已设置用户名:<b><?=$model->username; ?></b></div>
    	      <div class="caozuo box">
    	           <span>不可修改</span>
    	      </div>
    	      <?php else:?>
    	      <div class="info box">您还未设置用户名，设置后可用此用户名登录<b></b></div>
    	      <div class="caozuo box">
    	           <span><?= Html::a('设置', ['username'], ['class' =>'']) ?></span>
    	      </div>
    	      <?php endif;?>
    	  </li>
	      <li><div class="title box">
	       <p class="img_p"><span class="glyphicon glyphicon-ok color_green"></span> </p>
	       <h3 class="t3">登录密码</h3></div><div class="info box"><span class="a_color_red">为了您的账户安全，建议您定期更换登录密码</span></div><div class="caozuo box"><span><?= Html::a('修改', ['login-psd'], ['class' =>'']) ?> </span></div></li>
	      <li>
    	      <div class="title box">
    	          <p class="img_p">
        	       <?php if($model->mobile):?>
        	      <span class="glyphicon glyphicon-ok"></span>
        	      <?php else:?>
        	      <span class="glyphicon glyphicon-remove"></span>
        	      <?php endif;?>
        	      </p>
    	           <h3 class="t3">手机验证</h3>
    	      </div>
    	      <?php if($model->mobile):?>
    	      <div class="info box">您已验证的手机号码:<b><?= $model->mobile; ?></b></div>
    	      <div class="caozuo box">
    	           <span><?= Html::a('修改', ['change-mobile'], ['class' =>'']) ?></span>
    	      </div>
    	      <?php else:?>
    	      <div class="info box">您还未绑定手机号码，请立即绑定<b></b></div>
    	      <div class="caozuo box">
    	           <span><?= Html::a('绑定', ['bind-mobile'], ['class' =>'']) ?></span>
    	      </div>
    	      <?php endif;?>
    	  </li>
	      <li>
	      <div class="title box">
    	      <p class="img_p">
    	      <?php if(!$model->email): ?>
    	         <span class="glyphicon glyphicon-remove"></span>
    	      <?php else: ?>
    	         <span class="glyphicon glyphicon-ok"></span>
    	     <?php endif; ?>
    	      </p><h3 class="t3">绑定邮箱</h3>
	      </div>
	      <?php if(!$model->email): ?>
	          <div class="info box"><span class="a_color_red">您还未绑定邮箱，请立即绑定</span></div>
	          <div class="caozuo box"><span><?= Html::a('立即绑定', ['mail-validate'], ['class' =>'']) ?></span></div>
	      <?php else: ?>
	          <div class="info box">您已验证的邮箱：<b><?= $model->email ?></b></div>
	          <div class="caozuo box"><span>不可修改</span> </div>
	      <?php endif; ?>
	      </li>
	      <li class="hide">
	      <div class="title box">
	      <p class="img_p">
	      <?php if(empty($paypassword)): ?>
	         <span class="glyphicon glyphicon-remove"></span>
	      <?php else: ?>
	         <span class="glyphicon glyphicon-ok"></span>
	     <?php endif; ?>
	      </p>
	      <h3 class="t3">支付密码</h3>
	      </div>
	       <?php if(empty($paypassword)): ?>
	          <div class="info box"><span class="a_color_red">您还未设置登录密码，请立即设置</span></div>
	          <div class="caozuo box"><span><?= Html::a('立即设置', ['pay-psd'], ['class' =>'']) ?></span></div>
	      <?php else: ?>
	     		 <div class="info box"><span class="a_color_red">您已设置了支付密码</span></div>
	            <div class="caozuo box"><span><?= Html::a('修改', ['pay-psd'], ['class' =>'']) ?></span></div>
	      <?php endif; ?>
	      </li>
	      

	   </ul>
	</div>

</div>
