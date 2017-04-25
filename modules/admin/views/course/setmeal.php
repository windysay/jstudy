<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use backend\modules\v2\models\FreightTemplate;

$this->title = '所有套餐';
?>
<script>
	 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
			$(document).ready(function(){
				
				var success_message="<?=Yii::$app->session->getFlash('success') ?>"
				if(success_message){
					warn(success_message,1);
				}		    
				var error_message="<?=Yii::$app->session->getFlash('error') ?>"
				if(error_message){
					warn(error_message,0);
				}		    

				$(".operate_delete").click(function(){
					var id=$(this).attr("id");
					deleteAlert(id,'确定删除此套餐');
				})
				
			 });
			 
			function   ok_btn(obj){
				var id=$(obj).attr("data-id");
				$.ajax({//一个Ajax过程
					   type:"POST", //以post方式与后台沟通 
					   url:"<?= Url::toRoute('ajax-delete-course') ?>", 
					   dataType:'json',//从php返回的值以 JSON方式 解释
					   data:{"id":id},
					   cache:false,
					   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
							 if(msg==1){
								 $(".tr_"+id).fadeOut();
								 warn('删除成功',1);
							 }else{
								 warn('删除失败',0);
							 }
					   },
					   error:function(){
						   warn('删除失败',0)
				   	   }
				})//一个Ajax过程  
			}
	<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	
<div class="member-index container-fluid">
	<p class="f_top_title">课程套餐管理	<?= Html::a('添加课程套餐',['add-course'],['class'=>'btn btn-success pull-right']) ?></p>

	<table class="table table-hovered course_list">
 			<thead>
 			 	<tr>
			   <th>套餐</th>
			   <th>名称</th>
			   <th>上课券数量</th>
			   <th>原价/实价</th>
			   <th>销量</th>
			   <th>操作</th>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php if(!$model):?>
 			 	<tr><td colspan="7" id="no_data_remind">暂无课程套餐</td></tr>
 			 	<?php else :?>
 			 	<?php foreach ($model as $k=>$v):?>
 			 	<tr>
			      <td><img width="25" src="<?= $v['coverurl']?Yii::$app->homeUrl.'images/'.$v['coverurl']:null?>"></td>
			      <td><?= $v['name'] ?></td>
			      <td><?= $v['course_ticket']?></td>
			      <td><?= "￥".$v['price']?>/<?= "￥".$v['promotion_price']?></td>
			      <td><?= $v['sales'] ?></td>
			      <td>
			      <?= Html::a('编辑套餐',['update-course','id'=>$v['id']],['class'=>'','target'=>'_blank']) ?> /
			      <a href="javascript::void(0)" class="operate_delete" id="<?=$v['id'] ?>" data-type="open">撤销套餐</a>
			      </td>
			    </tr>
 			 	<?php endforeach;?>
 			 	<?php endif;?>
 			 </tbody>
 		</table>
		
</div>
