<?php 

use yii\helpers\Url;
use app\modules\student\models\Student;

$this->title="学生中心";
?>
<div class="site-index">
  
  <div class="xm_box">
         <div class="box_bd clearfix">
               <div class="uc_avatar">  
                   <a href="<?=Url::toRoute(['headimg']); ?>"><img class="show_cover" src="<?= Yii::$app->homeUrl.'images/'.($student['headimg']?$student['headimg']:"basic/basic_header.jpg") ?>"  /></a>
                </div>
                <div class="uc_info">
                    <h3 class="uc_welcome">
                    <span class="user_name"><?= Student::memberName($student) ?> </span>
                    <?php
						$h=date('G');
						if ($h<11) echo '早上好~';
						else if ($h<13) echo '中午好~';
						else if ($h<17) echo '下午好~';
						else echo '晚上好~';
					?>
                    </h3>
                    <h1 class="uc_welcome">您共购买<label>&nbsp;<?= $student->buy_ticket?>&nbsp;</label>张上课券，还剩余<label>&nbsp;<?=$student->course_ticket?>&nbsp;</label>张</h1>
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
