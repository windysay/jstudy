<?php 

use yii\helpers\Url;
$this->title="讲师资料";
?>
<div class="account-index">
    <div class="f_top_title">讲师认证资料</div>
    <div class="xm_box clearfix">
    	<div class="teacher_info_left pull-left">
    		<img class="headimg" alt="" src="<?= Yii::$app->homeUrl.'images/'.$model['headimg'] ?>">
    		<a class="hide" href="<?=Url::toRoute(["edit",'id'=>$model['id']]) ?>"><div class="update_info">完善个人档案</div></a>
    	</div>
    	<div class="teacher_info_right pull-right">
    		<p><label>姓名：</label><?= $model['name']?></p>
    		<p><label>邮箱：</label><?= $model['email']?></p>
    		<p><label>性别：</label><?= $model['sex']?"男":"女"?></p>
    		<p><label>Skype：</label><?= $model['skype']?></p>
            <p><label>QQ:</label><?= $teacher['qq']?></p>
    		<p><label style="margin-bottom:5px;">讲师介绍：</label><br><?= $model['info']?></p>
    	</div>
    </div>
</div>

<script type="text/javascript">
	 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
	   $(document).ready(function(){
		   
				
		})
			 
	<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	