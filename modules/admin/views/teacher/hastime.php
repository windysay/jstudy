<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Help;
use app\modules\teacher\models\Teacher;
$this->title="已提交课程讲师";

?>
<div class="member-index container-fluid">
	
	<p class="f_top_title"><?= Html::encode($this->title) ?></p>
	
	<table class="table table-hovered course_list">
		<thead>
		  <tr>
		   <th>头像</th>
		   <th>姓名</th>
		   <th>性别</th>
		   <th>登陆邮箱</th>
		   <th>Skype</th>
		   <th>状态</th>
		   <th>操作</th>
		  </tr>
		</thead>
		<tbody>
 			<?php if(!$classes):?>
 			<tr><td colspan="7" id="no_data_remind">暂无讲师记录</td></tr>
 			<?php else :?>
		    <?php foreach ($classes as $k=>$v):?>
		    <?php $teacher=Teacher::find()->where(['id'=>$v['teacher_id']])->asArray()->one();?>
		    <tr>
		      <td class="headimg"><img width="25" src="<?= $teacher['headimg']?Yii::$app->homeUrl.'images/'.$teacher['headimg']:null?>"></td>
		      <td><?= $teacher['name'] ?></td>
		      <td><?= $teacher['sex']==1?"男":"女"?></td>
		      <td><?= $teacher['email'] ?></td>
		      <td><?= $teacher['skype'] ?></td>
                <td><?= $teacher['status'] == 1 ? "正常" : "冻结" ?></td>
		      <td>
			  <?= Html::a('已提交时间表',['timetable','t'=>$teacher['id']],['class'=>'','target'=>'_blank']) ?> 
		      </td>
		    </tr>
		    <?php endforeach;?>
		    <tr>
		    </tr>
		    <?php endif;?>	
		</tbody>
    </table>
    
    <div class="fenye_main clearfix">
             <?= LinkPager::widget(['pagination' => $pages]); ?>
             <div class="count_box">讲师总数: <?=$count; ?> 人</div>
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
