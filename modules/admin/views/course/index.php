<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;

$this->title="未来预约记录";

$this->registerCssFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js',['depends' => [yii\web\JqueryAsset::className()]]);

?>
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_BEGIN') ?>
  
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


  $(".input_keywords").change(function(){
  	 var keywords=$(this).val();
	 var url="<?= Url::toRoute('index') ?>" ;
	 var url1='?keywords='+keywords;
     location.href=url+url1;
  })


	 $(".search_div .search_btn").click(function(){
			var keywords=$.trim($(this).siblings(".input_keywords").val());
			var url="<?= Url::toRoute('index') ?>" ;
			var url1='?keywords='+keywords;
			location.href=url+url1;
	   }) 
	
		$(".save_as_choosed").click(function(){
			var id=$(this).attr("data-id");
			deleteAlertMore(id,'确定将此课程设为可预约','ajax_save_as_choosed');
		})
		$(".delete_choosed").click(function(){
			var id=$(this).attr("data-id");
			deleteAlertMore(id,'确定删除此课程','ajax_delete_choosed');
		})
		$(".cancel_bespeaked").click(function(){
			var id=$(this).attr("data-id");
			deleteAlertMore(id,'确定取消该学生的预约','ajax_cancel_bespeaked');
		})
		
	})	
		function ajax_save_as_choosed(obj){
			var id=$(obj).attr("data-id");
			var status=1;
			var s=null;
			ajax_change_class(id,status,s);
		}
		function ajax_delete_choosed(obj){
			var id=$(obj).attr("data-id");
			var status=0;
			var s=null;
			ajax_change_class(id,status,s);
		}
		function ajax_cancel_bespeaked(obj){
			var id=$(obj).attr("data-id");
			var status=1;
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
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>

<div class="course-index">
		<div class="input-group  search_div pull-right  date form_date"  data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
			  <input type="text" class="form-control input_keywords " size="10"  value="<?= isset($_GET['keywords'])?$_GET['keywords']:date('Y-m-d',time()) ?>"  readonly>
			  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
 
 		<table class="table table-hovered course_list">
 			<thead>
 			 	<tr>
 			 		<td>日期</td>
 			 		<td>时间</td>
 			 		<td>老师</td>
 			 		<td>状态</td>
 			 		<td>学生</td>
 			 		<td>操作</td>
 			 		<td>查看详情</td>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php if(!$classes):?>
	 			 	<tr><td colspan="7" id="no_data_remind">暂无预约课程</td></tr>
	 			<?php endif;?>
 			 
 			 	<?php foreach ($classes as $k=>$v):?>
 			 	<tr>
 			 		<td><?= date('Y-m-d',$v['start_time']) ?></td>
 			 		<td><?= date('H:i',$v['start_time']).' - '.date('H:i',$v['end_time']) ?></td>
 			 		<?php $teacher=Teacher::find()->where('id=:id',[':id'=>$v['teacher_id']])->asArray()->one();?>
 			 		<td><?= Html::a($teacher['name'],['teacher/detail','id'=>$teacher['id']],['target'=>'_blank']) ?></td>
 			 		<td><?= Timetable::statusText($v,2) ?></td>
 			 		<?php if($v['student_id']):?>
 			 		<?php $student=Student::find()->where('id=:id',[':id'=>$v['student_id']])->asArray()->one(); ?>
 			 		<td><?= Html::a(Student::memberName($student),['student/detail','id'=>$student['id']],['target'=>'_blank']) ?></td>
 			 		<?php else:?>
 			 		<td></td>
 			 		<?php endif;?>
 			 		<td>
 			 			<?php if($v['status']==1):?>
 			 			<a href="<?= Url::toRoute(['bespeak-class','id'=>$v['id']]) ?>" target="_blank">预约</a> /
 			 			<a class="delete_choosed" href="javascript::void()" data-id="<?= $v['id'] ?>">删除</a>
 			 			<?php elseif($v['status']==0):?>
 			 			<a class="save_as_choosed" href="javascript::void()" data-id="<?= $v['id'] ?>">恢复</a>
 			 			<?php elseif($v['status']==2):?>
 			 			<a class="cancel_bespeaked" href="javascript::void()" data-id="<?= $v['id'] ?>">取消</a>
 			 			<?php endif;?>
 			 		</td>
 			 		<td><?= Html::a('查看',['class-detail','id'=>$v['id']],['target'=>'_blank']) ?></td>
 			 	</tr>
 			 	<?php endforeach;?>
 			 </tbody>
 		</table>
 		<div class="fenye_main pull-left clearfix" style="width:100%;"> 
	          <?= LinkPager::widget(['pagination' => $pages]); ?>
	          <div class="count_box">记录总数: <?=$count; ?> 节</div>
	     </div>
 		
</div><!-- site-index -->
