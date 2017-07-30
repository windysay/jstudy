<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Help;
$this->title="学员档案";

?>
<div class="member-index container-fluid">
	
	<p class="f_top_title"><?= Html::encode($this->title) ?></p>
	<div class="input-group  search_div pull-right" >
			  <input type="text" class="form-control input_keywords " value="<?= isset($_GET['keywords'])?$_GET['keywords']:null ?>" placeholder="手机/姓名/邮箱" style="width:200px;">
			  <span class="input-group-addon search_order"><span class="glyphicon glyphicon-search" style="pointer:cusour;"></span></span>
	</div>
	
	<table class="table table-hovered course_list">
 			<thead>
 			 	<tr>
			   <th>相片</th>
			   <th>用户名</th>
			   <th>性别</th>
			   <th>邮箱</th>
			   <th>手机</th>
                    <th>QQ</th>
			   <th>Skype</th>
			   <th>可用/购买上课券</th>
			   <th>状态</th>
			   <th>操作</th>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php if(!$model):?>
 			 	<tr><td colspan="7" id="no_data_remind">暂无学员记录</td></tr>
 			 	<?php else :?>
 			 	<?php foreach ($model as $k=>$v):?>
 			 	<tr>
			      <td class="headimg"><img width="25" src="<?= $v['headimg']?Yii::$app->homeUrl.'images/'.$v['headimg']:null?>"></td>
			      <td><?= $v['username']?:$v['realname'] ?></td>
			      <td><?= $v['sex']==1?"男":"女"?></td>
			      <td><?= $v['email'] ?></td>
			      <td><?= $v['mobile'] ?></td>
                    <td><?= $v['qq'] ?></td>
			      <td><?= $v['skype'] ?></td>
			      <td><?= $v['course_ticket'].'/'.$v['buy_ticket']?></td>
                    <td><?= $v['status'] == 1 ? "正常" : "冻结" ?></td>
			      <td>
			      <?= Html::a('详情',['detail','id'=>$v['id']],['class'=>'','target'=>'_blank']) ?> /
			      <?= Html::a('修改',['edit','id'=>$v['id']],['class'=>'','target'=>'_blank']) ?> /
                      <?= Html::a('课程', ['record', 'sid' => $v['id']], ['class' => '', 'target' => '_blank']) ?> /
			      <?php if($v['status']==1):?>
                      <a href="javascript::void(0)" class="deal_account" data-id="<?= $v['id'] ?>"
                         data-type="close">冻结</a>
			      <?php else:?>
                      <a href="javascript::void(0)" class="deal_account" data-id="<?= $v['id'] ?>"
                         data-type="open">解冻</a>
			      <?php endif;?>
			      </td>
			    </tr>
 			 	<?php endforeach;?>
 			 	<?php endif;?>
 			 </tbody>
 		</table>
    
    <div class="fenye_main clearfix">
             <?= LinkPager::widget(['pagination' => $pages]); ?>
             <div class="count_box">学生总数: <?=$count; ?> 人</div>
     </div>
	
</div>

     
 <script type="text/javascript">
 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
//////////////////////
	$(document).ready(function(){
		   $(".search_div .search_order").click(function(){
				var keywords=$.trim($(this).siblings(".input_keywords").val());
				var url="<?= Url::toRoute('index') ?>" ;
				var url1='?keywords='+keywords;
				location.href=url+url1;
		   })  
	 	$(".deal_account").click(function(){
				var type=$(this).attr("data-type");
				var id=$(this).attr("data-id");
				$.ajax({
						type:"POST",
						url:"<?=Url::toRoute("ajax-change-status") ?>",
						dataType:"json",
						data:{"type":type,'id':id},
						cache:false,
						success:function(msg){
							if(msg==1){
								 warn('操作成功',1);
								 window.location.href="";
							}else warn('操作失败',0) 
						}
					})
		 	})	
	////////////////////////
})
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
