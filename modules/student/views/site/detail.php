<?php 

use yii\helpers\Url;
$this->title="讲师档案详情";
?>
<style>
	.teacher_info_left{
		width:320px;
		padding:10px 0 0 0;
	}
	.teacher_info_right{
		width:590px;
		padding:10px 0 0 0px;
	}
	.teacher_info_right p{
		margin-bottom:10px;
		padding-bottom:10px;
		border-bottom:1px solid #ccc;
		font-size:14px;
	}
	.teacher_info_right p label{
		margin-right:5px;
		font-size:15px;
	}
	.teacher_info_left .headimg{
		width:320px;
	}
	.teacher_info_left .update_info{
		padding:15px 0;
		background:#EF3781;
		color:#fff;
		text-align: center;
		cursor:pointer;
	}
	.teacher_info_left .update_info:hover{
		background:#EF3750;
	}
	#teacher-info,#teacher-comment{
		width:400px;
		height:200px;
	}
</style>
<div class="account-index">
    <div class="f_top_title">讲师档案资料</div>
    <div class="xm_box clearfix">
    	<div class="teacher_info_left pull-left">
    		<img class="headimg" alt="" src="<?= $model['headimg']?Yii::$app->homeUrl.'images/'.$model['headimg']:null ?>" style="display: block;max-width: 220px;margin: 50px auto">
    	</div>
    	<div class="teacher_info_right pull-right">
    		<p><label>ID：</label><?= 'No.'.$model['id']?></p>
    		<p><label>姓名：</label><?= $model['name']?></p>
    		<p><label>邮箱：</label><?= $model['email']?></p>
    		<p><label>性别：</label><?= $model['sex']?"男":"女"?></p>
    		<p><label>Skype：</label><?= $model['skype']?></p>
            <p><label>QQ：</label><?= $model['qq']?></p>
       		<p><label>注册时间：</label><?= date("Y-m-d h:i:s",$model['createtime'])?></p>
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