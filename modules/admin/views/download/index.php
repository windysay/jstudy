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
use app\modules\student\models\OrderGoods;
$this->registerCssFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->title="所有文件记录";
?>

<div class="member-index container-fluid">
<p class="f_top_title">资源文件管理	<?= Html::a('上传资源',['add-material'],['class'=>'btn btn-success pull-right']) ?></p>
 		<table class="table table-hovered course_list">
 			<thead>
 			 	<tr>
 			 		<td></td>
 			 		<td>封面</td>
 			 		<td>标题</td>
 			 		<td>描述</td>
 			 		<td>文件大小</td>
 			 		<td>文件类型</td>
 			 		<td>创建时间</td>
 			 		<td>操作</td>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php if(!$model):?>
 			 	<tr><td colspan="7" id="no_data_remind">暂无订单记录</td></tr>
 			 	<?php else :?>
 			 	<?php foreach ($model as $k=>$v):?>
 			 	<tr id="box<?=$v['id'] ?>">
 			 		<td><?= $k+1 ?></td>
 			 		<td class="headimg"><img width="25" src="<?= $v['coverurl']?Yii::$app->homeUrl.'images/'.$v['coverurl']:null?>"></td>
 			 		<td title="<?= $v['title']?>"><?= Help::subtxt($v['title'],10)?></td>
 			 		<td title="<?= $v['description']?>"><?= Help::subtxt($v['description'],10)?></td>
 			 		<td><?= $v['size'].' byte'; ?></td>
 			 		<td><?= $v['type'];?></td>
 			 		<td><?= date('Y-m-d h:i:s',$v['createtime']) ?></td>
 			 		<td><?= Html::a('编辑',['update-material','id'=>$v['id']]) ?> / <a href="javascript::void(0)" class="caozuo_delete_a" data-id="<?=$v['id'] ?>">删除</a></td>
 			 	</tr>
 			 	<?php endforeach;?>
 			 	<?php endif;?>
 			 </tbody>
 		</table>
 		<div class="fenye_main pull-left clearfix" style="width:100%;"> 
	          <?= LinkPager::widget(['pagination' => $pages]); ?>
	          <div class="count_box">记录总数: <?=$count; ?> 条</div>
	     </div>
 		
</div><!-- site-index -->

 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_BEGIN') ?>
function ok_btn(obj){
	var id=$(obj).attr("data-id")
    $.ajax({//一个Ajax过程
		   type:"POST", //以post方式与后台沟通 
		   url:"<?=Url::toRoute("ajax-delete-material"); ?>", 
		   dataType:'json',//从php返回的值以 JSON方式 解释
		   data:{"id":id},
		   cache:false,
		   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
				if(msg==1){
    				 $("#box"+id).fadeOut();
    				 warn("删除成功",1) 
				}
		   }
		  })//一个Ajax过程  
}
$(document).ready(function(){
    $(".caozuo_delete_a").click(function(){
        var id=  $(this).attr('data-id')
        deleteAlert(id,'确定删除此素材')
  })
})
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>