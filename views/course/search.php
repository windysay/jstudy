<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\teacher\models\Teacher;
use app\models\Timetable;

$this->title='课程搜索';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Yii::$app->homeUrl.'css/teachers-timetable.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js',['depends' => [yii\web\JqueryAsset::className()]]);

?>
<div class="course-search width_1200">
	<div class="top_tool_div clearfix form-inline">
		<p class="btn btn-danger search_course_btn pull-right">搜索</p>

		  <div class="form-group form-inline  pull-right">
		    <label>上课时间</label>
		    <select class="form_time form-control">
		    	<option value="4" <?= isset($_GET['time'])&&$_GET['time']==4?"selected='selected'":null ?>>全天</option>
		    	<option value="1" <?= isset($_GET['time'])&&$_GET['time']==1?"selected='selected'":null ?>>上午</option>
		    	<option value="2" <?= isset($_GET['time'])&&$_GET['time']==2?"selected='selected'":null ?>>下午</option>
		    	<option value="3" <?= isset($_GET['time'])&&$_GET['time']==3?"selected='selected'":null ?>>晚上</option>
		    </select>
		  </div>
		  <div class="form-group form-inline  pull-right">
		    <label>上课日期</label>
		    <input class="form_date form-control" size="10" type="text" value="<?= isset($_GET['date'])?$_GET['date']:null ?>" readonly="readonly">
		  </div>
	</div>
	
	<div class="classes_list clearfix">
		<?php foreach ($course as $k=>$v):?>
		<?php $teacher=Teacher::find()->where('id=:teacher_id',[':teacher_id'=>$v['teacher_id']])->asArray()->one();?>
			<div class="per_class pull-left clearfix">
				<div class="info pull-left">
					<p class="date"><?= date('m月d日',$v['start_time']) ?></p>
					<p class="time"><?= date('H:i',$v['start_time']).' - '.date('H:i',$v['end_time']) ?></p>
					<p class="teacher">主讲师:<?= $teacher['name'] ?></p>
					<p class="status" data-id="<?= $v['id'] ?>"><?= Timetable::statusText($v,2) ?></p>
				</div>
				<div class="headimg pull-left">
					<a href="<?= Url::toRoute(['/course/timetable','t'=>$teacher['id']]) ?>" target="_blank">
					<img src="<?= Yii::$app->homeUrl.'images/'.$teacher['headimg'] ?>">
					</a>
					<?php if($v['status']==1): ?>
					<span class="bespeak_btn btn btn-sm btn-danger" data-id="<?= $v['id'] ?>">立即预约</span>
					<?php endif;?>
				</div>
			</div>
		<?php endforeach;?>
	</div>
</div>
			
 		
 
<script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){
	$('.form_date').datetimepicker({
        language:  'zh-CN',
        format: "yyyy-mm-dd",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		startDate:"<?= date('Y-m-d',$tomorrow_begin) ?>",
//		endDate:"<?= date('Y-m-d',$two_week_end) ?>",
		pickerPosition: "bottom-right"
    });
	$(".search_course_btn").click(function(){
		var date=$(".form_date").val();
		var time=$(".form_time").val();
		var href="<?= Url::toRoute(['/course/search']); ?>"
		window.location.href=href+"?date="+date+"&time="+time;
    })
    $(".bespeak_btn").click(function(){
		var id=$(this).attr("data-id");
		deleteAlertMore(id,'确定预约此课程','ajax_bespeak_class');
	})
})	
	function ajax_bespeak_class(obj){
			var id=$(obj).attr("data-id");
			$.ajax({//一个Ajax过程
				   type:"POST", //以post方式与后台沟通 
				   url:"<?= Url::toRoute('ajax-bespeak-class') ?>", 
				   dataType:'json',//从php返回的值以 JSON方式 解释
				   data:{"id":id},
				   cache:false,
				   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
						if(msg=='guest'){
							warn('请先登录',0);
						}else if(msg=='success'){
							$(".status[data-id='"+id+"']").text("预约成功");
							$(".bespeak_btn[data-id='"+id+"']").remove();
						}else if(msg=='no_ticket'){
							 warn('你没有上课券，请先去购买',0);
						}else if(msg=='error_class'||msg=='fail'){
							 warn('预约失败',0);
						}
				   },
				   error:function(){
					  
				   }
			})//一个Ajax过程  
	}
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>