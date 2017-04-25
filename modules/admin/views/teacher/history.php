<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\student\models\Student;

$this->title="课程历史记录";

$this->registerCssFile(Yii::$app->homeUrl.'css/teachers-timetable.css',['depends' => [yii\web\JqueryAsset::className()]]);
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
		var url="<?= Url::toRoute([$this->context->action->id,'t'=>$teacher['id']]); ?>";
		window.location.href=url+"&s="+begin_date+"&e="+end_date;
	})
})	

<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>

<div class="teacher-history clearfix">
	<ul class="nav nav-tabs" role="tablist">
	  <li role="presentation"><?= Html::a('预约时间表',['t'=>$teacher['id'],'timetable']) ?></li>
	  <li role="presentation"  class="active"><?= Html::a('课程历史记录') ?></li>
	</ul>
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
                <label for="dtp_input2" class="ontrol-label">按日期查询:</label>
                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                    <input class="form-control begin_date" size="10" type="text" value="<?= date('Y-m-d',$start_time) ?>" readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
            </div>
	</div>
	
 	<div class="teacher_info_basic pull-left">
 		<a href="<?= Url::toRoute(['teacher/detail','id'=>$teacher['id']]) ?>">
			<img class="headimg" src="<?= $teacher['headimg']?Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg']:null ;?>">
			<p class="name"><?= Html::encode($teacher['name']); ?></p>
			<p class="info"><?= Html::encode($teacher['info']); ?></p>
		</a>
	</div> 
 	<div class="classes_list pull-left">
 		<table class="table table-hovered">
 			<thead>
 			 	<tr>
 			 		<td>日期</td>
 			 		<td>上课时间</td>
 			 		<td>下课时间</td>
 			 		<td>状态</td>
 			 		<td>预约学生</td>
 			 		<td>查看详情</td>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php foreach ($classes as $k=>$v):?>
 			 	<tr>
 			 		<td><?= date('Y-m-d',$v['start_time']) ?></td>
 			 		<td><?= date('H:i',$v['start_time']) ?></td>
 			 		<td><?= date('H:i',$v['end_time']) ?></td>
 			 		<td><?= Timetable::statusText($v,2) ?></td>
 			 		<?php $student=Student::find()->where('id=:id',[':id'=>$v['student_id']])->asArray()->one(); ?>
 			 		<td><?= Html::a(Student::memberName($student),['student/detail','id'=>$student['id']],['target'=>'_blank']) ?></td>
 			 		<td><?= Html::a('查看详情',['course/class-detail','id'=>$v['id']],['target'=>'_blank']) ?></td>
 			 	</tr>
 			 	<?php endforeach;?>
 			 </tbody>
 		</table>
 		<div class="fenye_main pull-left clearfix" style="width:100%;"> 
	          <?= LinkPager::widget(['pagination' => $pages]); ?>
	          <div class="count_box">当前页课程总数: <?=$count; ?> 节</div>
	     </div>
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
