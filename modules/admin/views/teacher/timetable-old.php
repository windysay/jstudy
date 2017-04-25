<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\GlobalConst;
use yii\base\Object;
use app\models\Timetable;

$this->title="讲师时间表";

$this->registerCssFile(Yii::$app->homeUrl.'css/teachers-timetable.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="course-timetable timetable_list clearfix">
	<ul class="nav nav-tabs" role="tablist">
	  <li role="presentation" class="active"><?= Html::a('预约中时间表') ?></li>
	  <li role="presentation"><?= Html::a('历史预约',['t'=>$teacher['id'],'history']) ?></li>
	</ul>
	
	<div class="all">
	 	<div class="teacher_info pull-left">
			<img class="headimg" src="<?= Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg'] ;?>">
			<p class="name"><?= Html::encode($teacher['name']); ?></p>
			<p class="info"><?= Html::encode($teacher['info']); ?></p>
		</div> 
	 	<div class="time_info pull-left">
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
								   //查询这一天  这个时间段的课
								   $class=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher['id']])->andWhere('start_time='.$time_begin)/* ->andWhere('end_time<='.$time_end) */->one();
								   if($class){ 
								   		if ($class->start_time<=time()&&$class->end_time>time()&&$class->status==2){   //上课中
								   			$class->status=3;
								   			$class->save();
								   		}elseif($class->end_time<=time()&&($class->status==2||$class->status==3)){  //已经上完了
								   			$class->status=4;
								   			$class->save();
								   		}elseif($class->end_time<=time()&&($class->status==1)){  //已经过期了
								   			$class->status=5;
								   			$class->save();
								   		}
								   }
							?>
						<td>
							<?php if($class===null):?>
								<?php if($time_begin>=time()):?>
								<p class="time_block no_choose" data-status="no_choose" data-time_begin="<?= $time_begin ?>"  data-time_end="<?= $time_end ?>" data-date="<?= $v1 ?>"></p>
								<?php endif;?>
							<?php elseif($class->status==0):  //管理员已删除?>
								<?php if($class->start_time<=time()):?>
								<p class="time_block overtime">已取消</p>
								<?php else:?>
								<p class="time_block deleted" data-id="<?= $class->id ?>" data-status="deleted"  data-time_begin="<?= $time_begin ?>"  data-time_end="<?= $time_end ?>"  data-date="<?= $v1 ?>">已取消</p>
								<?php endif;?>
							<?php elseif($class->status==2):  //已预约 ?>
							<p class="time_block bespeaked" data-id="<?= $class->id ?>" data-status="bespeaked"  data-time_begin="<?= $time_begin ?>"  data-time_end="<?= $time_end ?>"  data-date="<?= $v1 ?>">已预约</p>
							<?php elseif($class->status==3):  ///上课中?>
							<p class="time_block inclass">上课中</p>
							<?php elseif($class->status==4):  ///已完成?>
							<p class="time_block completed">已上完</p>
							<?php elseif($class->status==5):  ///已过期?>
							<p class="time_block overtime">已过期</p>
							<?php else:?>
							<p class="time_block choosed" data-id="<?= $class->id ?>" data-status="choosed"  data-time_begin="<?= $time_begin ?>"  data-time_end="<?= $time_end ?>"  data-date="<?= $v1 ?>">可预约</p>
							<?php endif;?>
						</td>
						<?php endforeach;?>
					</tr>
					<?php endforeach;?>
				</table>
				<div class="clearfix">
					<p class="confirm_btn pull-right" data-id="1">确定修改</p>
				</div>
	 		</div>
 	
 		</div>
</div><!-- site-index -->
 
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){
	
	$(".week .time_block").click(function(){
		if($(this).hasClass("completed")||$(this).hasClass("inclass")||$(this).hasClass("overtime")){
			return false;
		}
		var status=$(this).attr("data-status");
		var id=$(this).attr("data-id");
		if(status=='deleted'){  //管理员删除
			$(this).attr('data-status','choosed');
			$(this).removeClass('deleted');
			$(this).addClass('choosed');
			$(this).addClass('save_change');
			$(this).text('可预约');
		}else if(status=='no_choose'){  //老师没有提交这个时间
			$(this).attr('data-status','choosed');
			$(this).removeClass('no_choose');
			$(this).addClass('choosed');
			$(this).addClass('save_change');
			$(this).text('可预约');
		}else if(status=='choosed'){  //  已提交时间，未预约
			if(id){  
				$(this).attr('data-status','deleted');
				$(this).removeClass('choosed');
				$(this).addClass('deleted');
				$(this).addClass('save_change');
				$(this).text('已取消');
			}else{
				$(this).attr('data-status','no_choose');
				$(this).removeClass('choosed');
				$(this).addClass('no_choose');
				$(this).removeClass('save_change');
				$(this).text('');
			}
		}else if(status=='bespeaked'){  //已预约
			$(this).attr('data-status','choosed');
			$(this).removeClass('bespeaked');
			$(this).addClass('choosed');
			$(this).addClass('save_change');
			$(this).text('可预约');
		}
	})
	 $(".week .confirm_btn").click(function(){
		 var id=$(this).attr("data-id");
		 deleteAlert(id,'确定保存修改吗');
	 }) 
	
})	
	function  ok_btn(obj){
		 var week_id=$(obj).attr('data-id');
		 var id_str='';
		 var status_str='';
		 var time_begin_str='';
		 var time_end_str='';
		 var date_str='';
		 $(".week"+week_id+" .save_change").each(function(){
			var id=$(this).attr("data-id");
			if(!id){
				var id=0;
			}
			var status=$(this).attr("data-status");
			var time_begin=$(this).attr("data-time_begin");
			var time_end=$(this).attr("data-time_end");
			var date=$(this).attr("data-date");
			id_str+=id+',';
			status_str+=status+',';
			time_begin_str+=time_begin+',';
			time_end_str+=time_end+',';
			date_str+=date+',';
		 })
		 if(id_str==''){
			warn("时间表未改变",0);
			return false;
		 }else{
			 ajax_save_timetable(id_str,status_str,time_begin_str,time_end_str,date_str);
		 }
		
	}
	
	function ajax_save_timetable(id_str,status_str,time_begin_str,time_end_str,date_str){
		 var tid="<?= $teacher['id'] ?>";
		 $.ajax({//一个Ajax过程
			   type:"POST", //以post方式与后台沟通 
			   url:"<?= Url::toRoute('ajax-save-timetable') ?>", 
			   dataType:'json',//从php返回的值以 JSON方式 解释
			   data:{"id_str":id_str,"status_str":status_str,"time_begin_str":time_begin_str,"time_end_str":time_end_str,"date_str":date_str,"tid":tid},
			   cache:false,
			   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
					if(msg==1){
						 warn('保存成功',1);
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
