<?php 

use yii\helpers\Url;
use app\models\CourseMeal;
use app\modules\student\models\OrderGoods;
$this->title="订单记录详情";
?>
<div class="account-index">
    <div class="f_top_title">订单记录详情</div>
    <div class="xm_box clearfix">
    	<?php $course=CourseMeal::findOne($model['course_id']);?>
    	<?php $orderGoods=OrderGoods::findOne(['order_sn'=>$model['order_sn']]);?>
        <div class="teacher_info_left pull-left">
    		<img class="headimg" alt="" src="<?= $course['coverurl']?Yii::$app->homeUrl.'images/'.$course['coverurl']:null ?>">
    	</div>
    	<div class="teacher_info_right pull-right">
    		<p><label>ID：</label><?= $model['order_sn']?></p>
    		<p><label>套餐名称：</label><?= $orderGoods['name']?></p>
    		<p><label>课程数量：</label><?= $course['course_ticket']?></p>
    		<p><label>套餐描述：</label><?= $course['description']?></p>
    		<p><label>姓名：</label><?= $model['c_name']?></p>
    		<p><label>联系方式：</label><?= $model['c_mobile']?></p>
    		<p class="hide"><label>支付方式：</label>支付宝支付</p>
    		<p><label>实付款：</label><?= $model['total_pay']?></p>
    		<p><label>状态：</label><?php if($model['order_status']==1) echo "交易成功"; else if($model['pay_status']==0) echo "未付款";else echo "交易失败";?></p>
       		<p><label>交易时间：</label><?= date("Y-m-d h:i:s",$model['createtime'])?></p>
    	</div>
    </div>
</div>

<script type="text/javascript">
	 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
	   $(document).ready(function(){
		   
				
		})
			 
	<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	