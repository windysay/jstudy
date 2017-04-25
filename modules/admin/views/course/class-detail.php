<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use app\modules\student\models\Student;

$this->title="课程信息";

?>
<div class="course-class-detail clearfix">
	<p class="f_top_title clearfix">
	     <?= Html::encode($this->title) ?>
	 </p>
	 <div class="clearfix">
		 <div class="teacher_info_basic pull-left">
		 	<a href="<?= Url::toRoute(['teacher/detail','id'=>$teacher['id']]) ?>">
			 	<img class="headimg" src="<?= $teacher['headimg']?Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg']:null ;?>">
				<p class="name"><?= Html::encode($teacher['name']); ?></p>
				<p class="info"><?= Html::encode($teacher['info']); ?></p>
			</a>
		 </div>
		 <div class="class_info pull-left">
			 <div class="info_div clearfix">
			 	<p class="head pull-left">上课日期</p>
			 	<p class="info pull-left"><?= date('Y-m-d',$class->start_time) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">上课开始时间</p>
			 	<p class="info pull-left"><?= date('H : i : s',$class->start_time) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">课程结束时间</p>
			 	<p class="info pull-left"><?= date('H : i : s',$class->end_time) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">讲师</p>
			 	<p class="info pull-left"><?= $teacher->name ?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">发布时间</p>
			 	<p class="info pull-left"><?= date('Y-m-d  H:i',$class->createtime) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">课程情况</p>
			 	<p class="info pull-left"><?= Timetable::statusText($class) ?></p>
			 </div>
			 <?php if($student):?>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">预约学生</p>
			 	<p class="info pull-left"><?= Student::memberName($student); ?></p>
			 </div>
			<?php endif;?> 
			 <?php if($student&&$class->ordertime):?>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">预约时间</p>
			 	<p class="info pull-left"><?= date('Y-m-d  H:i',$class->ordertime) ;?></p>
			 </div>
			<?php endif;?> 
			
			<?php $status=$class->status;?>
			<?php $start_time=$class->start_time;?>
			<?php $end_time=$class->end_time;?>
			<?php $time=time();?>
			<?php if(($status==1||$status==2||$status==0)&&$start_time>$time):  //可预约   已预约   已删除  但是上课时间还没到?>
			<div class="info_div clearfix">
			 	<p class="head pull-left">预约操作</p>
			 	<p class="info operate_div pull-left">
			 		<?php if($status==1):?>
			 		<a class="save_as_bespeaked btn btn-default btn-warning" href="<?= Url::toRoute(['bespeak-class','id'=>$class->id]) ?>">给学生预约此课程</a>
			 		<span class="delete_choosed btn btn-default btn-warning">删除此课程</span>
			 		<?php elseif($status==2):?>
			 		<span class="delete_choosed btn btn-default btn-warning">删除此课程</span>
			 		<span class="cancel_bespeaked btn btn-default btn-warning">取消该学生的预约</span>
			 		<?php elseif($status==0):?>
			 		<span class="save_as_choosed btn btn-default btn-warning">将此课程设为可预约</span>
			 		<?php endif;?>
				</p>
			 </div>
			<?php endif;?> 
		</div>	
	</div>	
</div><!-- site-index -->
 
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){
	$(".save_as_choosed").click(function(){
		deleteAlertMore(1,'确定将此课程设为可预约','ajax_save_as_choosed');
	})
	$(".delete_choosed").click(function(){
		deleteAlertMore(1,'确定删除此课程','ajax_delete_choosed');
	})
	$(".cancel_bespeaked").click(function(){
		deleteAlertMore(1,'确定取消该学生的预约','ajax_cancel_bespeaked');
	})
	
})	
	
	function ajax_save_as_choosed(){
		var status=1;
		var id="<?= $class->id ;?>";
		var s=null;
		ajax_change_class(id,status,s);
	}
	function ajax_delete_choosed(){
		var status=0;
		var id="<?= $class->id ;?>";
		var s=null;
		ajax_change_class(id,status,s);
	}
	function ajax_cancel_bespeaked(){
		var status=1;
		var id="<?= $class->id ;?>";
		var s=null;
		ajax_change_class(id,status,s);
	}
	
	function ajax_change_class(id,status,s){
		$.ajax({//一个Ajax过程
			   type:"POST", //以post方式与后台沟通 
			   url:"<?= Url::toRoute('ajax-change-class') ?>", 
			   dataType:'json',//从php返回的值以 JSON方式 解释
			   data:{"id":id,"status":status,"s":s},
			   cache:false,
			   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
					if(msg==1){
						var url=window.location.href;
						warnRedirect('保存成功',1,url);
					}else{
						 warn('保存失败',0);
					}
			   },
			   error:function(){
				   warn('保存失败',0);
			   }
		})//一个Ajax过程  
	
	}
	
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
