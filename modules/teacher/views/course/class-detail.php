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
		 <div class="student_teacher pull-left">
		 	<a  class="teacher">
			 	<img class="headimg" src="<?= $teacher['headimg']?Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg']:null ;?>">
				<p class="name"><?= Html::encode($teacher['name']); ?></p>
				<p class="info"><?= Html::encode($teacher['info']); ?></p>
			</a>
			<?php if($student):?>
		 	<a href="<?= Url::toRoute(['student-info','s'=>$class['student_id']]) ?>">
			 	<img class="headimg" src="<?= $student['headimg']?Yii::$app->urlManager->baseUrl.'/images/'.$student['headimg']:null ;?>">
				<p class="name"><span class="student">生徒 ：</span> <?= Html::encode(Student::memberName($student)); ?></p>
			</a>
			<?php endif;?>
		 </div>
		 <div class="class_info pull-left">
			 <div class="info_div clearfix">
			 	<p class="head pull-left">上课日付</p>
			 	<p class="info pull-left"><?= date('Y-m-d',$class->start_time) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">上课开始時間</p>
			 	<p class="info pull-left"><?= date('H : i : s',$class->start_time) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">课程结束時間</p>
			 	<p class="info pull-left"><?= date('H : i : s',$class->end_time) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">发布時間</p>
			 	<p class="info pull-left"><?= date('Y-m-d  H:i',$class->createtime) ;?></p>
			 </div>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">课程情况</p>
			 	<p class="info pull-left"><?= Timetable::statusText($class) ?></p>
			 </div>
			 <?php if($student):?>
			  <div class="info_div clearfix">
			 	<p class="head pull-left">生徒</p>
			 	<p class="info pull-left"><?= Html::a(Student::memberName($student),['student-info','s'=>$student->id]) ?></p>
			 </div>
			 <?php endif;?>
			 <?php if($class->ordertime):?>
			 <div class="info_div clearfix">
			 	<p class="head pull-left">预约時間</p>
			 	<p class="info pull-left"><?= date('Y-m-d  H:i',$class->ordertime) ;?></p>
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
		deleteAlertMore(1,'确定取消该生徒的预约','ajax_cancel_bespeaked');
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
