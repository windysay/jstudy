<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
use app\modules\student\models\TimetableCancel;

$this->title="取消预约记录";

$this->registerCssFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_BEGIN') ?>
  
$(document).ready(function(){
	$(".search_btn").click(function(){
		var begin_date=$(".begin_date").val();
		var end_date=$(".end_date").val();
		if(!begin_date||!end_date){
			warn('请选择正确的日期',0);
			return false;
		}
		var url="<?= Url::toRoute([$this->context->action->id]); ?>";
		window.location.href=url+"?s="+begin_date+"&e="+end_date;
	})
})	

<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>

<div class="course-canceled">

	 <div class="top_tool_div clearfix form-inline">
			<span class="pull-right btn btn-danger search_btn">查看</span>
		
		    <div class="form-group pull-right">
                <label for="dtp_input2" class="ontrol-label">至:</label>
                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control end_date" size="10" type="text" value="<?= date('Y-m-d',$end_time) ?>"  readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
            </div>
	 		<div class="form-group pull-right">
                <label for="dtp_input2" class="ontrol-label">按上课日期查询:</label>
                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                    <input class="form-control begin_date" size="10" type="text" value="<?= date('Y-m-d',$start_time) ?>" readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
            </div>
	  </div>
	 <div class="course_list">
 		<table class="table table-hovered">
 			<thead>
 			 	<tr>
 			 		<td>日期</td>
 			 		<td>上课时间</td>
 			 		<td>老师</td>
 			 		<td>取消方式</td>
 			 		<td>取消时间</td>
 			 	</tr>
 			 </thead>
 			 
 			 <tbody>
 			 	<?php if(!$classes):?>
	 			 	<tr><td colspan="5" id="no_data_remind">无相关记录</td></tr>
	 			 	<?php endif;?>
 			 	
 			 	<?php foreach ($classes as $k=>$v):?>
 			 	<tr>
 			 		<td><?= date('Y-m-d',$v['start_time']) ?></td>
 			 		<td><?= date('H:i',$v['start_time']).' - '.date('H:i',$v['end_time']) ?></td>
 			 		<?php $teacher=Teacher::find()->where('id=:id',[':id'=>$v['teacher_id']])->asArray()->one();?>
 			 		<td><?= Html::a($teacher['name'],['site/teacher-info','id'=>$teacher['id']],['target'=>'_blank']) ?></td>
 			 		<td><?= TimetableCancel::cacenlType($v['type']) ?></td>
 			 		<td><?= date('Y-m-d H:i',$v['canceltime']) ?></td>
 			 	</tr>
 			 	<?php endforeach;?>
 			 </tbody>
 		</table>
 	</div>	
 	<div class="fenye_main pull-left clearfix"> 
          <?= LinkPager::widget(['pagination' => $pages]); ?>
          <div class="count_box">记录总数: <?=$count; ?> 节</div>
     </div>
 		
</div><!-- site-index -->
 
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
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

<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
