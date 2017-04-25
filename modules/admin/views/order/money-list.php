<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
use app\components\Help;
use app\modules\student\models\OrderGoods;
$this->registerCssFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->title="资金流水记录";
?>
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_BEGIN') ?>
  
	$(document).ready(function(){
		$(document).ready(function(){
			$('.form_date').datetimepicker({
		        language:  'zh-CN',
		        weekStart: 1,
		        todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				forceParse: 0
		    });
		})	
		
		$(".search_btn").click(function(){
			var begin_date=$(".begin_date").val();
			var end_date=$(".end_date").val();
			if(!begin_date||!end_date){
				warn('请选择正确的日期',0);
				return false;
			}
			var url="<?= Url::toRoute([$this->context->action->id]); ?>";
			window.location.href=url+"?start="+begin_date+"&end="+end_date;
		})
	})	
		

<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>

<div class="course-index">
	<div class="top_tool_div clearfix form-inline">
			<span class="pull-right btn btn-danger search_btn">查看</span>
		
		    <div class="form-group pull-right">
                <label for="dtp_input2" class="ontrol-label">至:</label>
                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control end_date" size="10" type="text" value="<?= date('Y-m-d',isset($_GET['end'])?strtotime($_GET['end']):time()) ?>"  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
            </div>
	 		<div class="form-group pull-right">
                <label for="dtp_input2" class="ontrol-label">按日期查询:</label>
                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                    <input class="form-control begin_date" size="10" type="text" value="<?= isset($_GET['start'])?date('Y-m-d',strtotime($_GET['start'])):date('Y-m-1',time()) ?>" readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
            </div>
	  </div>

 		<table class="table table-hovered course_list">
 			<thead>
 			 	<tr>
 			 		<td>订单编号</td>
 			 		<td>收支类型</td>
 			 		<td>来源</td>
 			 		<td>描述</td>
 			 		<td>金额</td>
 			 		<td>支付时间</td>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php if(!$model):?>
 			 	<tr><td colspan="7" id="no_data_remind">暂无资金记录</td></tr>
 			 	<?php else :?>
 			 	<?php foreach ($model as $k=>$v):?>
 			 	<tr>
 			 		<td><?= $v['order_sn'] ?></td>
 			 		<td><?= $v['pay_type']==1?'收入':'支出'?></td>
 			 		<td><?= $v['pay_channel']==1?'支付宝支付':'微信支付'?></td>
 			 		<td title="<?=$v['content']?>"><?= Help::subtxt($v['content'],15)?></td>
 			 		<td><?= $v['total_fee'] ?></td>
 			 		<td><?= date('Y-m-d H:i',$v['createtime']) ?></td>
 			 	</tr>
 			 	<?php endforeach;?>
 			 	<?php endif;?>
 			 </tbody>
 		</table>
 		<div class="fenye_main pull-left clearfix" style="width:100%;"> 
	          <?= LinkPager::widget(['pagination' => $pages]); ?>
	          <div class="count_box">记录总数: <?=$count; ?> 条</div>
	     </div>
 		
</div><!-- site-index -->
