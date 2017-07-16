<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\Merchant;
use \app\components\Help;
use common\models\AddressCode;
use backend\components\UploadImages64;

$this->title="课程套餐定制确认";
?>
 
	<div class="cart-confirm action-basic">

   <div class="order_info clearfix">
    <div class="box t_info">
      <div class="gou_box"><img  src="<?=Yii::$app->homeUrl.'images/basic/gou_icon.png' ?>" /></div>
        <h2 class="t2">您的订单已提交成功，等待付款中哦！</h2>
      <p class="jine_p">应付金额 : <span>￥<?=Help::xiaoshu($order['total_pay'])?></span></p>
   </div>

    <div class="box pay_info clearfix">
      <div class="t_name">支付方式</div>
      <div class="t_main">
       <div class="img_box clearfix">
       <span class="alipay_icon pay_icon active pull-left"><img src="<?=Yii::$app->homeUrl.'images/basic/alipay_icon.png'; ?>" /></span>
        <span class="wxpay_icon pay_icon pull-left hide"><img src="<?=Yii::$app->homeUrl.'images/basic/icon_WePayLogo_180_60.png'; ?>" /></span>
      </div>
       <div class="liji_pay clearfix">
        <div class="money_b s_b"><span class="money_sum">￥<?=Help::xiaoshu($order['total_pay']); ?></span></div>
        <a class="alipay_now" target="_blank" href="<?=Url::toRoute('/alipay/pay-now?sn='.$_GET['sn'])?>" ><div class="tpay_b s_b"><span class="text_pay">立即支付</span></div></a>
        <a class="wxpay_now" style="display:none;" target="_blank" href="<?=Url::toRoute('/wxpay/pay-now?sn='.$_GET['sn'])?>" ><div class="tpay_b s_b"><span class="text_pay">立即支付</span></div></a>
      </div>
      </div>
   </div>
    <div class="box order_info clearfix">
      <div class="t_name">订单信息</div>
      <div class="t_main">
       <p>订单编号：<a href="<?=Url::toRoute(['/student/order/detail','sn'=>$_GET['sn']]) ?>" class="color_blue" target="_blank"><?=$order['order_sn']?></a></p>
       <p>收货人信息：<?=$order['c_name']?> <?=$order['c_mobile']?></p>
      </div>
   </div>


   </div> 
 
   </div><!--cart-confirm-->
 
 <script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
 
   
//////////////////////
$(document).ready(function(){
 
 $(".text_pay").click(function(){
      var ok_url="<?=Url::toRoute('/student/order/index'); ?>";
      var again_url="<?=Url::toRoute('alipay/pay-now?sn='.$_GET['sn']); ?>";   
      var html='<div class="mask_main"><div class="main alert_pay_main"><div class="top_box"><p>正在支付</p></div><div class="main_box"><p class="pay_ok"><a href="'+ok_url+'">支付完成</a></p><p class="pay_again"><a target="_blank" href="'+again_url+'">重新支付</a></p></div></div></div>';
      $("body").append(html)
      $(".mask_main .alert_pay_main").slideDown(350)
    })
 $(".alipay_icon").click(function(){
	$(".wxpay_icon").removeClass("active");
	$(this).addClass("active");
	$(".wxpay_now").hide();
	$(".alipay_now").show();
 })
 $(".wxpay_icon").click(function(){
	$(".alipay_icon").removeClass("active");
	$(this).addClass("active");
	$(".alipay_now").hide();
	$(".wxpay_now").show();
 })

////////////////////////
})
    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
