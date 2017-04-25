<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Member;
use common\components\CacheDate;
use app\modules\student\models\OrderGoods;

$title='订单详情';
$this->title=$title;

?>
<div class="order-detail">
<p class="f_top_title"><?= $title ?></p>
   <div class="detail_main">
   
 <div class="order_delivery_status">
            <ul class="order_delivery_steps clearfix">
                    <li class="step step_first step_now">
                        <div class="title"> 下单 </div>
                    </li>
                    <li class="step <?= $order['pay_status']==1?'step_now':null ?>">
                      	<div class="title">付款</div>
                    </li>
                     <li class="step  step_last <?= $order['order_status']==1?'step_now':null ?>">
                        <div class="title">交易成功</div>
                    </li>
             </ul>
      </div>
 
	<table  class="table table-bordered order_table">
			<thead>
				<tr>
					<th style="text-align:center;">商品</th>
					<th>名称</th>
					<th>单价</th>
					<th>上课券数量</th>
				</tr>
			</thead>
 	 		<tbody>
 	 			<?php $orderGoods=OrderGoods::findOne(['order_sn'=>$order['order_sn']]);?>
	           <tr class="table_content">
	           		<td class="td_goods_thumb" style="text-align:center;">
	           			<img alt="" src="<?=  Yii::$app->homeUrl.'images/'.$orderGoods['coverurl'] ?>">
	           		</td>
	           		<td class="td_name" style="border-right:solid 1px #eee;"><?=$orderGoods['name']?></td>
	           		<td class="td_price"  style="border-right:solid 1px #eee;">
	           			<?php if($orderGoods['price']&&$orderGoods['price']!=$orderGoods['promotion_price']):?>
	           			<p class="price"><del><?= "￥".$orderGoods['price']?></del></p>
	           			<?php endif;?>
	           			￥<?=Html::encode($orderGoods['promotion_price'])?>
	           		</td>
	           		<td class="td_sum"><?= $orderGoods['total_count']?></td> 
	           </tr>
		    </tbody>
		</table>
      		<div class="shop_cart_box pull-right">
           <div class=" shop_cart_total">
                <ul class="">
                    <li>商品总价：<span>￥<b id="J_cartProductMoney"><?=Html::encode($order['total_price'])?></b></span></li>
                    <li>活动优惠：<span>-￥<b id="J_cartActivityMoney"><?=$order['coupon_money']?></b></span></li>
                </ul>
                <p class="total_price">实付金额：<span>￥<strong id="J_cartTotalPrice"><?=Html::encode($order['total_pay'])?></strong></span></p>
         </div>
      </div>
	  <div>
	  </div>		
        <dl class="dl_info clearfix  pull-left">
           <dt class="t2">订单信息</dt>
           <dd class="">
            <p><span class="bold_text">订单编号: </span> <span><?=Html::encode($order['order_sn'])?></span></p>
              <p><span class="bold_text">下单时间: </span> <?= date('Y-m-d H:i:s',$order['createtime']) ?></span></p>
           <p class=""><span><span class="sp_name bold_text">姓名</span>：<?=Html::encode($order['c_name'])?></span><span class="sp_tel"></p>
           <p><span class="bold_text">电话: </span><?=Html::encode($order['c_mobile'])?></span></p>
           <?php if($order['c_message']):?>
           <p><span class="bold_text">留言: </span><?=Html::encode($order['c_message'])?></p>
           <?php endif;?>
           </dd>
       </dl>
   
    </div>
 
 </div><!-- order-list -->
 

     
 <script type="text/javascript">
 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
//////////////////////
	$(document).ready(function(){

 
 
	 
////////////////////////
})
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
