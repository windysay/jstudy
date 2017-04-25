<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use app\modules\student\models\Student;
use yii\grid\GridView;
use yii\widgets\LinkPager;

$this->title="课程信息";

?>
<div class="course-class-detail clearfix">
	<p class="f_top_title clearfix">
	     <?= Html::encode($this->title) ?>
	 </p>
	 <div class="clearfix">
		 <div class="teacher_info_basic pull-left">
		 	<img class="headimg" src="<?= Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg'] ;?>">
			<p class="name"><?= Html::encode($teacher['name']); ?></p>
			<p class="info"><?= Html::encode($teacher['info']); ?></p>
		 </div>
		 <div class="student_list pull-left clearfix">
		 	<div class="top_tool_div clearfix form-inline">
			 	<span class="remind pull-left">请选择学生</span>
			 	<span class="pull-left btn btn-sm btn-success save_btn">提交</span>
			 	<span class="pull-right btn btn-danger search_btn">搜索</span>
				<input type="text" class="form-control pull-right input_keywords"  value="<?= $keywords ?>" placeholder="学生姓名 " >
			</div> 	
			<?php foreach ($students as $k=>$v):?>
			<div class="per_student pull-left">
				<label class="pull-left"><input class="choose_student" type="radio" name="student" value="<?= $v['id'] ?>">选择</label>
				<img class="headimg pull-left" src="<?= $v['headimg']?Yii::$app->urlManager->hostInfo.'/images/'.$v['headimg']:null ?>">
				<p class="name pull-left"><?= Student::memberName($v) ?></p>
			</div>
			<?php endforeach;?>
			 <div class="fenye_main pull-left clearfix" style="width:100%;">
	             <?= LinkPager::widget(['pagination' => $pages]); ?>
	             <div class="count_box">当前页学生: <?=$count; ?> 人</div>
	     	</div>
		</div>	
	</div>	
</div><!-- site-index -->
 
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){

	$(".top_tool_div .search_btn").click(function(){
		var keywords=$.trim($(this).siblings(".input_keywords").val());
		var url="<?= Url::toRoute([$this->context->action->id,'id'=>$class->id]) ?>" ;
		var url1=url+'&stu='+keywords;
		location.href=url1;
   }) 
		
	$(".save_btn").click(function(){
		var student=$(".choose_student:checked").val();
		if(!student){
			warn('您还没有选择学生',0);
			return false;
		}	
		deleteAlertMore(1,'确定给该学生选课','ajax_bespeak_class');
	})
	
})	
	
	function ajax_bespeak_class(){
		var status=2;
		var id="<?= $class->id ;?>";
		var s=$(".choose_student:checked").val();
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
						var url="<?= Yii::$app->request->referrer ?>";
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
