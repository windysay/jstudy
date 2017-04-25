<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\models\CourseMeal;
use app\modules\student\models\OrderGoods;
use app\components\Help;

$this->title = '上课券购买记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index site_basic">
	<div class="main_z clearfix">
		<div class="main clearfix">
			<div class="box_bd clearfix">
               <div class="uc_avatar">  
                   <a href="<?=Url::toRoute(['site/index']); ?>"><img class="show_cover" src="<?= Yii::$app->homeUrl.'images/'.(Yii::$app->user->identity->headimg?Yii::$app->user->identity->headimg:"basic/basic_header.jpg") ?>"  /></a>
                </div>
                <div class="uc_info">
                    <h3 class="uc_welcome"><span class="user_name"><?= Yii::$app->user->identity->username; ?>
                    </span>
                    <?php
						$h=date('G');
						if ($h<11) echo '早上好~';
						else if ($h<13) echo '中午好~';
						else if ($h<17) echo '下午好~';
						else echo '晚上好~';
					?>
                    </h3>
                    <h1 class="uc_welcome">您共购买<label>&nbsp;<?= $student->buy_ticket?>&nbsp;</label>张上课券，还剩余<label>&nbsp;<?=$student->course_ticket?>&nbsp;</label>张</h1>
                </div>
                <a href="<?=Url::toRoute("/course/index")?>"><div class="uc_button pull-right">购买上课券</div></a>
            </div>
            <p class="f_top_title">上课券购买记录</p>
 			<?php if ($model):?>           
 			<div class="list_main">
 			<table class="order_table">
 			<tbody>
 			<?php foreach ($model as $k=>$v):?>
 			<tr  class="separate_row " data-sn="<?= $v['order_sn']?>"><td  colspan="9"></td></tr> 
 			<tr class="table_info" data-sn="<?= $v['order_sn']?>">
 			<td  colspan="9" class="clearfix">
 				<p class="pull-left creattime"><span  class=""><?= Html::encode(date("Y-m-d H:i",$v['createtime']))?></span></p> 
 				<p class="pull-left">订编编号: <span><?=Html::encode($v['order_sn'])?></span></p>
				<a href="javascript::void(0)" class="operate_delete  pull-right"  >删除</a>
 			</td>
 			</tr>
 			 <tr class="table_content" data-sn="<?= $v['order_sn'] ?>">
				<td class="td_goods_thumb">
				<?php $orderGoods=OrderGoods::findOne(['order_sn'=>$v['order_sn']]); $rowsapn=count($orderGoods);?>
					<img alt="" src="<?=  Yii::$app->homeUrl.'images/'.$orderGoods['coverurl'] ?>">
				</td>
				<td class="td_name"><?=$orderGoods['name']?></td>
				<td class="td_price">
				    ￥<?=Html::encode($orderGoods->promotion_price)?>
				</td>
				 <td class="td_sum">上课券 x <?= $orderGoods->total_count ?></td> 
				<td class="td_order_status" rowspan="<?=$rowsapn?>">
					<?php if($v['pay_status']==0)://未付款?>
					     <p>待付款</p>
					<?php else: //已付款?>
						<p class="succss">交易成功</p>
					<?php endif;?>
					<?=Html::a('订单详情',['detail','sn'=>$v['order_sn']],['target'=>'_blank'])?>
				</td>				 
				<td class="td_operate" rowspan="<?=$rowsapn?>">
				     <?php if($v['pay_status']==0): //未付款?>
				     <?=   Html::a('去付款',['/alipay/confirm','sn'=>$v['order_sn']],['class'=>'btn btn-sm btn-primary pay_btn','target'=>'_blank'])?>
				     <?php endif;?>
				 </td>				 
			</tr>
 			<?php endforeach;?>
 			</tbody>
 			</table>
 			</div>
			<?php else :?>
			<div class="main_error_center" id="no_data_remind">暂无购买记录</div>
			<?php endif;?>
		</div>
	</div>
</div>
<script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
	$(document).ready(function(){
		  $(".table_info .operate_delete").click(function(){
			  var sn=$(this).parents(".table_info").attr("data-sn");
			  deleteAlert(sn,'确定删除此订单');
		  })
		})
 	function ok_btn(obj){
				var sn=$(obj).attr("data-id");
				$.ajax({//一个Ajax过程
					   type:"POST", //以post方式与后台沟通 
					   url:"<?= Url::toRoute('ajax-delete-order') ?>", 
					   dataType:'json',//从php返回的值以 JSON方式 解释
					   data:{"sn":sn},
					   cache:false,
					   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
						   if(msg==1){
								$(".order_table tr[data-sn='"+sn+"']").remove();
								$(".order_table tr[data-sn='"+sn+"']").remove();
								$(".order_table tr[data-sn='"+sn+"']").remove();
								$(".separate_row[data-sn='"+sn+"']").remove();
							}
							else if(msg==0){
								 warn('该订单不能删除',0);
							}
							else if(msg==3){
								 warn('删除失败',0);
							}
					   },
					   error:function(){
						   warn('删除失败',0);
					   }
				})//一个Ajax过程  
			}
<?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>



