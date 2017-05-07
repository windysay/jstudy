<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\GlobalConst;
use yii\base\Object;
use app\models\Timetable;
use app\modules\student\models\Student;

$this->title="シフト提出"; //我的课程

?>
<div class="course-timetable timetable_list clearfix">

	<div class="all clearfix">
	 	<div class="teacher_info pull-left">
			<img class="headimg" src="<?= Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg'] ;?>">
			<p class="name"><?= Html::encode($teacher['name']); ?></p>
			<p class="info"><?= Html::encode($teacher['info']); ?></p>
			<div class="course_count">
				<p><span><?= date('m',$before_month_begin) ?>月授業数</span><a class="count" href="<?= Url::toRoute(['history','s'=>date('Y-m-d',$before_month_begin),'e'=>date('Y-m-d',$before_month_end)]) ?>" target="_blank"><?= $before_month_count ?></a></p>
				<p><span><?= date('m',$this_month_begin) ?>月授業数</span><a class="count" href="<?= Url::toRoute(['history','s'=>date('Y-m-d',$this_month_begin),'e'=>date('Y-m-d',$this_month_end)]) ?>" target="_blank"><?= $this_month_count ?></a></p>
			</div>
			<table class="table table-hovered">
				<?php foreach ($future_course as $k=>$v): ?>
				<tr>
					<?php if($v['student_id']):?>
					<?php $student=Student::find()->where('id=:id',[':id'=>$v['student_id']])->asArray()->one();?>
					<td><?= Html::a(Student::memberName($student),['student-info','s'=>$v['student_id']],['target'=>'_blank']) ?></td>
					<?php else:?>
					<td></td>
					<?php endif;?>
					<td><?= Timetable::statusText($v,2) ?></td>
					<td><?= date('Y-m-d H:i',$v['start_time']) ?></td>
				</tr>
				<?php endforeach;?>
			</table>
		</div> 
	 	<div class="time_info pull-right">
	 		<div class="week week1">
	 			<table class="table table-bordered">
	 				<tr class="week_date">
	 					<td class="time_td"></td>
	 					<?php foreach ($week1 as $k1=>$v1): ?>
	 					<td>
	 						<?php $w=date('w',$v1);?>
	 						<p class="week_text <?= $w==6||$w==0?'weekend':null ?>"><?= $weekText[$w]; ?></p>
	 						<p class="date_text"><?= date('m-d',$v1); ?></p>
	 					</td>
	 					<?php endforeach;?>
	 				</tr>
	 				<?php foreach ($day_times as $kk1=>$vv1):?>
					<tr>
						<td><?= $vv1['text2'] ?></td>
						<?php foreach ($week1 as $k1=>$v1):?>
							<?php  
								   $date=date('Y-m-d',$v1);
								   $time_begin=$date.' '.$vv1['begin'];   //如10:00:00 
								   $time_end=$date.' '.$vv1['end'];  //11:00:00
								   $time_begin=strtotime($time_begin);
								   $time_end=strtotime($time_end);
								   //查询这一天  这个時間段的课
								   $class=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher['id']])->andWhere('start_time='.$time_begin)/* ->andWhere('end_time<='.$time_end) */->one();
							?>
						<td>
							<?php if($class===null):?>
								<?php if($week1_can_submit&&$time_begin>time()):?>
								<p class="time_block no_choose" data-time_begin="<?= $time_begin ?>"  data-time_end="<?= $time_end ?>" data-date="<?= $v1 ?>" title="点击添加" data-toggle="tooltip" data-placement="top"></p>
								<?php endif;?>
							<?php elseif($class->status==0):  //管理员已删除?>
								<p class="time_block overtime">已取消</p>
							<?php elseif($class->status==2):  //已预约 ?>
							<a class="time_block bespeaked"  href="<?= Url::toRoute(['student-info','s'=>$class->student_id]) ?>" target="_blank">已预约</a>
							<?php elseif($class->status==3):  ///上课中?>
							<p class="time_block inclass">上课中</p>
							<?php elseif($class->status==4):  ///已完成?>
							<a class="time_block completed" href="<?= Url::toRoute(['class-detail','c'=>$class->id]) ?>" target="_blank">已完成</a>
							<?php elseif($class->status==5):  ///已过期?>
							<p class="time_block overtime">已过期</p>
							<?php elseif($class->status==1):?>
								<span class="time_block choosed glyphicon glyphicon-ok"></span>
							<?php endif;?>
						</td>
						<?php endforeach;?>
					</tr>
					<?php endforeach;?>
				</table>
				<div class="clearfix">
					<span class="confirm_btn btn btn-lg pull-right <?= $week1_can_submit?null:'disabled' ?>" data-id="1">確認</span>
					<p class="submit_remind pull-right" style="line-height:22px;">※ シフト提出は一回のみになります、提出後の変更は不可ですので、週単位で提出してください。</p>
				</div>
	 		</div>
	 		<div class="week week2">
	 			<table class="table table-bordered">
	 				<tr class="week_date">
	 					<td class="time_td"></td>
	 					<?php foreach ($week2 as $k2=>$v2): ?>
	 					<td>
	 						<?php $w=date('w',$v2);?>
	 						<p class="week_text <?= $w==6||$w==0?'weekend':null ?>"><?= $weekText[$w]; ?></p>
	 						<p class="date_text"><?= date('m-d',$v2); ?></p>
	 					</td>
	 					<?php endforeach;?>
	 				</tr>
	 				<?php foreach ($day_times as $kk2=>$vv2):?>
					<tr>
						<td><?= $vv2['text2'] ?></td>
						<?php foreach ($week2 as $k2=>$v2):?>
							<?php  
								   $date=date('Y-m-d',$v2);
								   $time_begin=$date.' '.$vv2['begin'];   //如10:00:00 
								   $time_end=$date.' '.$vv2['end'];  //11:00:00
								   $time_begin=strtotime($time_begin);
								   $time_end=strtotime($time_end);
								   //查询这一天  这个時間段的课
								   $class=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher['id']])->andWhere('start_time='.$time_begin)/* ->andWhere('end_time<='.$time_end) */->one();
							?>
						<td>
							<?php if($class===null):?>
								<?php if($week2_can_submit&&$time_begin>time()):?>
								<p class="time_block no_choose" data-time_begin="<?= $time_begin ?>"  data-time_end="<?= $time_end ?>" data-date="<?= $v2 ?>" title="点击添加" data-toggle="tooltip" data-placement="top"></p>
								<?php endif;?>
							<?php elseif($class->status==0):  //管理员已删除?>
								<p class="time_block overtime">已取消</p>
							<?php elseif($class->status==2):  //已预约 ?>
							<a class="time_block bespeaked" href="<?= Url::toRoute(['student-info','s'=>$class->student_id]) ?>"  target="_blank">已预约</a>
							<?php elseif($class->status==3):  ///上课中?>
							<p class="time_block inclass">上课中</p>
							<?php elseif($class->status==4):  ///已完成?>
							<p class="time_block completed">已完成</p>
							<?php elseif($class->status==5):  ///已过期?>
							<p class="time_block overtime">已过期</p>
							<?php elseif($class->status==1):?>
								<span class="time_block choosed glyphicon glyphicon-ok"></span>
							<?php endif;?>
						</td>
						<?php endforeach;?>
					</tr>
					<?php endforeach;?>
				</table>
				<div class="clearfix">
					<span class="confirm_btn btn btn-lg pull-right <?= $week2_can_submit?null:'disabled' ?>" data-id="2">確認</span>
					<p class="submit_remind pull-right" style="line-height:22px;">※ シフト提出は一回のみになります、提出後の変更は不可ですので、週単位で提出してください。</p>
				</div>
	 		</div>
 		</div>
 	</div>
</div><!-- site-index -->
 
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){
	
	$(".week").on("click",".no_choose",function(){
		$(this).removeClass('no_choose');
		$(this).addClass('now_choosed glyphicon glyphicon-ok');
		$(this).addClass('save_change');
		$(this).attr('data-original-title','点击取消');
	})
	$(".week").on("click",".now_choosed",function(){
		$(this).removeClass('now_choosed glyphicon glyphicon-ok');
		$(this).addClass('no_choose');
		$(this).removeClass('save_change');
		$(this).attr('data-original-title','点击添加');
	})
	 $(".week .confirm_btn").click(function(){
		 if($(this).hasClass("disabled")){
				return false;
		 }
		 var week_id=$(this).attr('data-id');
		 if($(".week"+week_id+" .save_change").length<=0){
			warn("请先选择您的上课時間",0);
			return false;
		 }
		 var id=$(this).attr("data-id");
		 deleteAlert(id,'提出後の変更は出来ませんので、これでよろしいですか');
	 }) 
	
})	
	function  ok_btn(obj){
		 var week_id=$(obj).attr('data-id');
		 var time_begin_str='';
		 var time_end_str='';
		 var date_str='';
		 $(".week"+week_id+" .save_change").each(function(){
			var time_begin=$(this).attr("data-time_begin");
			var time_end=$(this).attr("data-time_end");
			var date=$(this).attr("data-date");
			time_begin_str+=time_begin+',';
			time_end_str+=time_end+',';
			date_str+=date+',';
		 })
		 if(time_begin_str==''){
			warn("请先选择您的上课時間",0);
			return false;
		 }else{
			 $(".week"+week_id+" .confirm_btn").addClass('disabled');
			 ajax_save_timetable(time_begin_str,time_end_str,date_str,week_id);
		 }
		
	}
	
	function ajax_save_timetable(time_begin_str,time_end_str,date_str,week_id){
		 $.ajax({//一个Ajax过程
			   type:"POST", //以post方式与后台沟通 
			   url:"<?= Url::toRoute('ajax-save-timetable') ?>", 
			   dataType:'json',//从php返回的值以 JSON方式 解释
			   data:{"time_begin_str":time_begin_str,"time_end_str":time_end_str,"date_str":date_str},
			   cache:false,
			   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
					if(msg==1){
						 warn('保存成功',1);
						 $(".now_choosed").addClass("choosed");
						 $(".now_choosed").removeClass("now_choosed");
						 $(".no_choose").remove();
					}else{
						 warn('保存失败',0);
						 $(".week"+week_id+" .confirm_btn").removeClass('disabled');
					}
			   },
			   error:function(){
				   warn('保存失败',0);
				   $(".week"+week_id+" .confirm_btn").removeClass('disabled');
			   }
		})//一个Ajax过程  
		
	}
	
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
