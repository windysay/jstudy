<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = '课程设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index site_basic">
	<div class="main_z clearfix">
		<div class="main clearfix">
			
			<ul class="course_ul clearfix">
			<?php if ($model):?>
			<?php foreach ($model as $v):?>
				<li class="pull-left">
					<p class="name"><?= $v['name']?></p>
					<img alt="" src="<?= Yii::$app->homeUrl.'images/'.$v['coverurl']?>">
					<p class="description"><?= str_replace(" ","<br>",$v['description']);?></p>
					<p class="ticket"><label>共<?= $v['course_ticket']?>节课</label></p>
					<p class="price">￥<label><?= $v['promotion_price']?></label></p>
					<p class="topay"><button class="btn btn-default btn-lg btn_topay" data-id="<?= $v['id']?>">立即购买</button></p>
				</li>
			<?php endforeach;?>
			<?php else :?>
			<div class="main_error_center">暂无课程套餐</div>
			<?php endif;?>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
	$("body").on("click",".btn_topay",function(){ //提交订单
			var goods_id=$(this).attr("data-id");
			var user_id="<?= Yii::$app->user->id;?>";
			if(user_id=="") window.location.href="<?=Url::toRoute("account/login") ?>";
			 $.ajax({//一个Ajax过程
				 type:"POST", //以post方式与后台沟通 
		 		 url:"<?=Url::toRoute("ajax-submit-order"); ?>", 
		 		 dataType:'json',//从php返回的值以 JSON方式 解释
		 		 data:{"goods_id":goods_id,},
		 		 cache:false,
		 		 success:function(data){
			 		 if(data!=0) {
				 		 window.location.href="<?= Url::toRoute("/alipay/confirm")?>"+"?sn="+data;
			 		 } else warn("系统繁忙,请稍候再试",0);
		 		 }
		 	})
		})
<?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>



