<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\components\Help;

$this->title='讲师时间表';

$this->registerCssFile(Yii::$app->homeUrl.'css/teachers-timetable.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="course-teacher-time teacher_list">
	
	<div class="top_tool_div clearfix form-inline">
		<span class="pull-right btn btn-danger search_btn">搜索</span>
		<input type="text" class="form-control pull-right input_keywords"  value="<?= $keywords ?>" placeholder="讲师姓名/邮箱" >
	</div>
	
	<div class="all clearfix">
		<?php foreach ($teachers as $k=>$v):?>
   		<div class="pull-left per_teacher">
   			<a href="<?= Url::toRoute(['timetable','t'=>$v['id']]) ?>" target="_blank">
   			<img class="headimg" src="<?= $v['headimg']?Yii::$app->urlManager->hostInfo.'/images/'.$v['headimg']:null ?>">
   			<p class="name"><?= $v['name'] ?></p>
   			<p class="info" title="<?=$v['info'] ?>"><?= Help::subtxt($v['info'], 40) ?></p>
   			</a>
   		</div>
    	<?php endforeach;?>
	</div>
	 
    <div class="fenye_main clearfix">
             <?= LinkPager::widget(['pagination' => $pages]); ?>
             <div class="count_box">讲师总数: <?=$count; ?> 人</div>
     </div>
	
</div>
			
 		
 

     
 <script type="text/javascript">
 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
//////////////////////
$(document).ready(function(){
	$(".top_tool_div .search_btn").click(function(){
		var keywords=$.trim($(this).siblings(".input_keywords").val());
		var url="<?= Url::toRoute([$this->context->action->id]) ?>" ;
		var url1=url+'?kw='+keywords;
		location.href=url1;
   }) 
	 
	
})
////////////////////////
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
