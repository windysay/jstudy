<?php 

use yii\helpers\Url;
$this->title="管理员中心";
?>
<div class="site-index">
  
  <div class="xm_box">
     <div class="box_bd clearfix">
      <div class="jumbotron">
		  <h1>
		  <?php
			$h=date('G');
			if ($h<11) echo 'Iperapera管理员，早上好~';
			else if ($h<13) echo 'Iperapera管理员，中午好~';
			else if ($h<17) echo 'Iperapera管理员，下午好~';
			else echo 'Iperapera管理员，晚上好~';
			?>
		  </h1><br/><br/><br/><br/>
	
		  <p>网站流量由CNZZ提供统计 <a target="_blank" href="http://dwz.cn/4CiP5D">查看教程</a></p>
		  <p><a class="btn btn-primary btn-lg" target="_blank" href="http://tongji.cnzz.com" role="button">网站流量查询</a></p>
	  </div>
 
       </div>
   </div>

  <div class="xm_box">
  
   </div>

 
 
 </div><!-- site-index -->
 

     
 <script type="text/javascript">
 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
//////////////////////
	$(document).ready(function(){
    var register_success="<?=Yii::$app->session->getFlash('register_success') ?>";
	if(register_success){
		warn(register_success,1);
	}
 
	 
	////////////////////////
})
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
