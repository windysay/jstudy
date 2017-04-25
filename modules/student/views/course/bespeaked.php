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

$this->title="已预约课程";
?>
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_BEGIN') ?>
  
$(document).ready(function(){
		$(".cancel_bespeaked").click(function(){
			var id=$(this).attr("data-id");
			deleteAlertMore(id,'确定取消该课程的预约','ajax_cancel_bespeaked');
		})
})	
		function ajax_cancel_bespeaked(obj){
			var id=$(obj).attr("data-id");
			ajax_cancel_class(id);
		}
		
		function ajax_cancel_class(id){
			$.ajax({//一个Ajax过程
				   type:"POST", //以post方式与后台沟通 
				   url:"<?= Url::toRoute('ajax-cancel-class') ?>", 
				   dataType:'json',//从php返回的值以 JSON方式 解释
				   data:{"id":id},
				   cache:false,
				   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
						if(msg==1||msg==2){
							warn('已成功取消预约',1);
							$("table tr[data-id='"+id+"']").remove();
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
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>

<div class="course-bespeaked">
		
		<div class="course_list">
	 		<table class="table table-hovered">
	 			<thead>
	 			 	<tr>
	 			 		<td>日期</td>
	 			 		<td>时间</td>
	 			 		<td>老师</td>
	 			 		<td>状态</td>
	 			 		<td>操作</td>
	 			 		<td>课程详情</td>
	 			 	</tr>
	 			 </thead>
	 			 <tbody>
	 			 	<?php if(!$classes):?>
	 			 	<tr><td colspan="6" id="no_data_remind">暂无预约课程</td></tr>
	 			 	<?php endif;?>
	 			 	
	 			 	<?php foreach ($classes as $k=>$v):?>
	 			 	<tr data-id="<?= $v['id'] ?>">
	 			 		<td><?= date('Y-m-d',$v['start_time']) ?></td>
	 			 		<td><?= date('H:i',$v['start_time']).' - '.date('H:i',$v['end_time']) ?></td>
	 			 		<?php $teacher=Teacher::find()->where('id=:id',[':id'=>$v['teacher_id']])->asArray()->one();?>
	 			 		<td><?= Html::a($teacher['name'],['site/teacher-info','id'=>$teacher['id']],['target'=>'_blank']) ?></td>
	 			 		<td><?= Timetable::statusText($v,2) ?></td>
	 			 		<td>
	 			 			<?php $tomorrow=Help::getZeroStrtotime('tomorrow');  //明天0点时间?>
	 			 			<?php if($v['start_time']>$tomorrow):  //明天以后的才能取消预约?>
	 			 			<a class="cancel_bespeaked" href="javascript::void()" data-id="<?= $v['id'] ?>">取消预约</a>
	 			 			<?php else:?>
	 			 			<span style="">不可取消</span>
	 			 			<?php endif;?>
	 			 		</td>
	 			 		<td><?= Html::a('查看',['class-detail','id'=>$v['id']],['target'=>'_blank']) ?></td>
	 			 	</tr>
	 			 	<?php endforeach;?>
	 			 </tbody>
	 		</table>
	 	</div>
 		<div class="fenye_main pull-left clearfix" style="width:100%;"> 
	          <?= LinkPager::widget(['pagination' => $pages]); ?>
	          <div class="count_box">记录总数: <?=$count; ?> 节</div>
	     </div>
	     	<div class="remind">注：如需取消预约，请提前一天</div>	
 		
</div><!-- site-index -->
