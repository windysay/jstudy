<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\GlobalConst;
use yii\base\Object;
use app\models\Timetable;

$this->title="讲师时间表";

$this->registerCssFile(Yii::$app->homeUrl.'css/teachers-timetable.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="teacher-timetable timetable_list clearfix">
	<ul class="nav nav-tabs" role="tablist">
	  <li role="presentation" class="active"><?= Html::a('预约时间表') ?></li>
	  <li role="presentation"><?= Html::a('课程历史记录',['t'=>$teacher['id'],'history']) ?></li>
	</ul>
	
	<div class="all">
	 	<div class="teacher_info_basic pull-left">
	 		<a href="<?= Url::toRoute(['teacher/detail','id'=>$teacher['id']]) ?>">
				<img class="headimg" src="<?= $teacher['headimg']?Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg']:null ;?>">
				<p class="name"><?= Html::encode($teacher['name']); ?></p>
				<p class="info"><?= Html::encode($teacher['info']); ?></p>
			</a>
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
								   		}elseif($class->start_time<=time()&&($class->status==1)){  //已经过期了
								   			$class->status=5;
								   			$class->save();
								   		}
								   }
							?>
						<td>
							<?php if($class===null):?>
								<?php if($time_begin>=time()):?>
								<a class="time_block no_choose" href="<?= Url::toRoute(['add-timetable','t'=>$teacher['id'],'s'=>$time_begin,'e'=>$time_end]) ?>"  title='在此处给该讲师添加课程' data-toggle='tooltip' data-placement='top' ></a>
								<?php endif;?>
							<?php elseif($class->status==0):  //管理员已删除?>
								<?php if($class->start_time<=time()):?>
								<a class="time_block overtime">已删除</a>
								<?php else:?>
								<a class="time_block deleted" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已删除</a>
								<?php endif;?>
							<?php elseif($class->status==2):  //已预约 ?>
							<a class="time_block bespeaked" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已预约</a>
							<?php elseif($class->status==3):  ///上课中?>
							<a class="time_block inclass" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">上课中</a>
							<?php elseif($class->status==4):  ///已完成?>
							<a class="time_block completed" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已结束</a>
							<?php elseif($class->status==5):  ///已过期?>
							<a class="time_block overtime" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已过期</a>
							<?php elseif($class->status==1):?>
							<a class="time_block choosed"  href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">可预约</a>
							<?php endif;?>
						</td>
						<?php endforeach;?>
					</tr>
					<?php endforeach;?>
				</table>
	 		</div>
	 		<div class="week week1">
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
								   //查询这一天  这个时间段的课
								   $class=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher['id']])->andWhere('start_time='.$time_begin)/* ->andWhere('end_time<='.$time_end) */->one();
								   if($class){ 
								   		if ($class->start_time<=time()&&$class->end_time>time()&&$class->status==2){   //上课中
								   			$class->status=3;
								   			$class->save();
								   		}elseif($class->end_time<=time()&&($class->status==2||$class->status==3)){  //已经上完了
								   			$class->status=4;
								   			$class->save();
								   		}elseif($class->start_time<=time()&&($class->status==1)){  //已经过期了
								   			$class->status=5;
								   			$class->save();
								   		}
								   }
							?>
						<td>
							<?php if($class===null):?>
								<?php if($time_begin>=time()):?>
								<a class="time_block no_choose" href="<?= Url::toRoute(['add-timetable','t'=>$teacher['id'],'s'=>$time_begin,'e'=>$time_end]) ?>"  title='在此处给该讲师添加课程' data-toggle='tooltip' data-placement='top' ></a>
								<?php endif;?>
							<?php elseif($class->status==0):  //管理员已删除?>
								<?php if($class->start_time<=time()):?>
								<a class="time_block overtime">已删除</a>
								<?php else:?>
								<a class="time_block deleted" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已删除</a>
								<?php endif;?>
							<?php elseif($class->status==2):  //已预约 ?>
							<a class="time_block bespeaked" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已预约</a>
							<?php elseif($class->status==3):  ///上课中?>
							<a class="time_block inclass" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">上课中</a>
							<?php elseif($class->status==4):  ///已完成?>
							<a class="time_block completed" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已结束</a>
							<?php elseif($class->status==5):  ///已过期?>
							<a class="time_block overtime" href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">已过期</a>
							<?php else:?>
							<a class="time_block choosed"  href="<?= Url::toRoute(['course/class-detail','id'=>$class->id]) ?>" target="_blank">可预约</a>
							<?php endif;?>
						</td>
						<?php endforeach;?>
					</tr>
					<?php endforeach;?>
				</table>
	 		</div>
 	
 		</div>
</div><!-- site-index -->
 
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){
	
	
})	

	
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
