<?php 

use yii\helpers\Url;
$this->title="管理员中心";
?>
<div class="site-index">
  
  <div class="xm_box">
         <div class="box_bd clearfix">
                <div class="uc_info">
                    <h3 class="uc_welcome"><span class="user_name"><?= $admin->username ?>
                    </span>
                    <?php
						$h=date('G');
						if ($h<11) echo '早上好~';
						else if ($h<13) echo '中午好~';
						else if ($h<17) echo '下午好~';
						else echo '晚上好~';
					?>
                    </h3>
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
