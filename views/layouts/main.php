<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\modules\admin\models\Admin;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>

 <div class="header_nav clearfix">
 	<div class="header_center clearfix">
 		 <div class="home pull-left">
 			 <a href="<?= Url::toRoute(['/site/index']) ?>" class="home pull-left" >Iperaperaへようこそ！</a>
 		 </div>
	     
	     <div class="site_box pull-right">
	     		<?php if(!\Yii::$app->user->isGuest):?>
                <?php $student=Student::findOne(Yii::$app->user->id)?>
                <div class="dropdown">
		           	  <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
					    <?= Student::memberName($student) ?>
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['/student/site/index']) ?>">账户中心</a></li>
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/logout']); ?>">退出登录</a></li>
					  </ul>
				</div>
				<?php elseif(!\Yii::$app->teacher->isGuest):?>
                <?php $teacher=Teacher::findOne(Yii::$app->teacher->id)?>
                <div class="dropdown">
		           	  <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
					    <?= $teacher['name']?$teacher['name']:$teacher['email'] ?>
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['/teacher/site/index']) ?>">マイホーム</a></li>
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/teacher-logout']); ?>">ログアウト</a></li>
					  </ul>
				</div>
				<?php elseif(!\Yii::$app->admin->isGuest):?>
                <?php $admin=Admin::findOne(Yii::$app->admin->id)?>
                <div class="dropdown">
		           	  <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
					    <?= $admin['username'] ?>
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['/admin/site/index']) ?>">账户中心</a></li>
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/admin-logout']); ?>">退出登录</a></li>
					  </ul>
				</div>
				<?php else:?>
					<a class="login" href="<?= Url::toRoute(['/account/login']) ?>">登录</a>
                	<span class="ge">|</span>
                	<a class="register" href="<?= Url::toRoute(['/site/zeren']) ?>">注册</a>
				<?php endif;?>
         </div>  
         <?php $nowModule=$this->context->module->id; ?>
         <?php $nowControl=$this->context->id; ?>
         <?php $nowAction=$this->context->action->id; ?>
         <div class="box nav_list pull-right">
	      <ul class="nav_ul">
	       <li class="list chuci_li_a"><a href="<?=Url::toRoute(['/site/index']);?>" class="<?= $nowModule!='student'&&$nowModule!='admin'&&$nowModule!='teacher'&&$nowControl=='site'&&$nowAction=='index'?'active':null  ?>">初次见面</a>
             <div class="er_nav">
              <a href="<?=Url::toRoute('site/benzhan');?>">关于本站</a>
               <a href="<?=Url::toRoute('site/shangke');?>">关于上课</a>
              <a href="<?=Url::toRoute('site/jiangshi');?>">关于讲师</a>
              <a href="<?=Url::toRoute('site/xuefei');?>">关于费用</a>                           
 			 </div>
	       </li>
	       <li class="list"><a href="<?=Url::toRoute(['/course/index']);?>" class="<?= $nowModule!='student'&&$nowModule!='admin'&&$nowModule!='teacher'&&$nowControl=='course'&&$nowAction=='index'?'active':null  ?>">课程购买</a></li>
	       <li class="list"><a href="<?=Url::toRoute(['/course/teachers']);?>" class="<?= $nowModule!='student'&&$nowModule!='admin'&&$nowModule!='teacher'&&$nowControl=='course'&&$nowAction=='teachers'?'active':null  ?>">讲师们</a></li>
	       <li class="list hide"><a href="<?=Url::toRoute(['/site/benzhan']);?>" class="<?= $nowModule!='student'&&$nowModule!='admin'&&$nowModule!='teacher'&&$nowControl=='site'&&$nowAction=='benzhan'?'active':null  ?>">关于本站</a></li>
	       <li class="list hide"><a href="<?=Url::toRoute(['/course/download']);?>" class="<?= $nowModule!='student'&&$nowModule!='admin'&&$nowModule!='teacher'&&$nowControl=='course'&&$nowAction=='download'?'active':null  ?>">自由教材</a></li>
	       <li class="list"><a href="<?=Url::toRoute(['/site/contact']);?>" class="<?= $nowModule!='student'&&$nowModule!='admin'&&$nowModule!='teacher'&&$nowControl=='site'&&$nowAction=='contact'?'active':null  ?>">联系我们</a></li> 

	      </ul>
	     </div>
                
	  </div>  
 </div>

<div class="main_main">
	<center>
	<?= Breadcrumbs::widget([  
    'homeLink'=>['label' => '主页','url' => Yii::$app->homeUrl],  
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],  
	]) ?>  
	</center>
 	<?=$content; ?>
</div>
<footer class="footer">
  <p>Copyright © 2015 - 2016&nbsp; 萌卜（上海）文化传播有限公司  &nbsp;&nbsp;粤ICP备14027430号</p>
</footer>

<div style="display:none;">
<script src="http://s95.cnzz.com/z_stat.php?id=1260608742&web_id=1260608742" language="JavaScript"></script>
</div>


<?php $this->endBody(); ?>
</body>
<script>
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
$(document).ready(function(){
	$('.my_site_menu_dropdown').dropdownHover();
	 $('*[data-toggle="tooltip"]').tooltip();
	 $('*[data-toggle="popover"]').popover();

	 /** Yii Flash提示*/
	 var warn_message="<?=Yii::$app->session->getFlash('success') ?>";
	if(warn_message){
		warn(warn_message,1)
	}
	var error_message="<?=Yii::$app->session->getFlash('error') ?>";
	if(error_message){
		warn(error_message,0)
	}
	 
 
  $(".nav_ul .chuci_li_a").hover(function(){
    $('.nav_ul .er_nav').slideDown(300);
  },function(){
  	$('.nav_ul .er_nav').slideUp();
  })
 

})
 
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
</html>
<?php $this->endPage(); ?>
