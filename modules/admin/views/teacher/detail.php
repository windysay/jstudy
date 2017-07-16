<?php 

use yii\helpers\Url;
$this->title="讲师档案详情";
?>
<div class="account-index">
    <div class="f_top_title">讲师档案资料</div>
    <div class="xm_box clearfix">
    	<div class="teacher_info_left pull-left">
    		<img class="headimg" alt="" src="<?= $model['headimg']?Yii::$app->homeUrl.'images/'.$model['headimg']:null ?>">
    	</div>
    	<div class="teacher_info_right pull-right">
    		<p><label>姓名：</label><?= $model['name']?></p>
    		<p><label>性别：</label><?= $model['sex']?"男":"女"?></p>
    		<p><label>Skype：</label><?= $model['skype']?></p>
            <p><label>QQ：</label><?= $model['qq']?></p>         
    		<p><label>状态：</label><?= $model['status']==1?'正常使用中':'冻结中'?></p>
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