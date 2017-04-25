<?php 

use yii\helpers\Url;
$this->title="讲师中心";
?>
<div class="order-index">
  
  <div class="xm_box">
         <div class="box_bd clearfix">
               <div class="uc_avatar">  
                   <a href="<?=Url::toRoute(['account/headimg']); ?>"><img class="show_cover" src="<?= Yii::$app->homeUrl.'images/'.$teacher->headimg?:"basic/basic_header.jpg" ?>"  /></a>
                </div>
                <div class="uc_info">
                    <h3 class="uc_welcome"><span class="user_name"><?= $teacher->name; ?>先生、
                    </span>
                    <?php
						$h=date('G');
						if ($h<11) echo 'おはようございます~';
                    else if ($h<13) echo 'こんにちは~';
						else if ($h<17) echo '午後は良いです~';
						else echo 'こんばんは~';
					?>
                    </h3>
                    <p>  skype ID：<?= $teacher['skype'] ?></p>
                  <!--   <span class="icon">绑定手机</span><span class="sep icon">|</span><span class="icon">绑定邮箱</span> -->
                </div>
            </div>
   </div>

  <div class="xm_box">
       <h3 class="t1">個人情報</h3>

    <div class="xm_box clearfix">
      <div class="teacher_info_left pull-left">
        <img class="headimg" alt="" src="<?= Yii::$app->homeUrl.'images/'.$teacher['headimg'] ?>">
        <a class="hide" href="<?=Url::toRoute(["edit",'id'=>$teacher['id']]) ?>"><div class="update_info">完善个人档案</div></a>
      </div>
      <div class="teacher_info_right pull-right">
        <p><label>名前：</label><?= $teacher['name']?></p>
        <p><label>メイルアドレス：</label><?= $teacher['email']?></p>
        <p><label>性别：</label><?= $teacher['sex']?"男":"女"?></p>
        <p><label>Skype：</label><?= $teacher['skype']?></p>
        <p><label>QQ:</label><?= $teacher['qq']?></p>
      </div>
    </div>

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
