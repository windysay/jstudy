<?php 
use yii\helpers\Url;
use common\models\AlipayNotify;
use common\components\Help;
 
?>
<div class="main-main">
 <div class="pay_success clearfix">
 	<div class="pay_success_content">
        <label><span class="glyphicon glyphicon-ok"></span>支付成功啦。</label>
 		<a href="<?= Url::toRoute(['/student/order/detail','sn'=>$_GET['out_trade_no']]) ?>"><div class="back_home">查看订单</div></a>
 	</div>
 </div>
</div>