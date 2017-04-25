<?php
use app\assets\BackendAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\modules\admin\components\AdminMenu;
use app\components\GlobalConst;


BackendAsset::register($this);
?>
<?php
$menu=new AdminMenu;
$menuTop=$menu->menuTop();//顶部菜单
$menuLeftCon=$menu->menuLeftCon($this->context->id);//左边菜单的控制器
?>
 
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title?Html::encode($this->title):Yii::$app->id; ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="header">
      <div class="header_main clearfix">
         <div class="logo_box pull-left">
            <?=GlobalConst::ADMIN_WEB_NAME;?>
         </div>  
         <div class="nav_box pull-left">
            <ul class="nav_list">
              <?php foreach($menuTop as $k=>$v): ?>
                     <?php if($menu->menuLeftCon($v)==$menu->menuLeftCon($this->context->id)): ?>
                     <li class="nav_active_index"><a href="<?= Url::toRoute([$v.'/index']) ?>"><?php echo $menu->menuTopName($v); ?></a></li>
                     <?php else: ?>
                     <li><a href="<?= Url::toRoute([$v.'/index']) ?>"><?= $menu->menuTopName($v); ?></a></li>
                     <?php endif; ?>
              <?php endforeach; ?>
            </ul>
         </div>  
         <div class="site_box pull-right">
			<div class="dropdown">
             <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
			     设置
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['account/index']) ?>">账户设置</a></li>
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/admin-logout']); ?>">退出登录</a></li>
			  </ul>
			</div>		
         </div>  
       </div>
    </div>
 
    <div class="wrap">
        <div class="container">
        <div class="menu_left pull-left">
           <?php foreach($menuLeftCon as $k=>$v): ?>
               <ul class="m_menu">
                    <li class="top"><a href="<?= Url::toRoute([$v.'/index']) ?>"><?= $menu->menuLeftName($v); ?></a></li>
                    <?php  foreach($menu->menuList($v) as $k2=>$v2): ?>
                    <?php if($k2==$this->context->action->id&&$this->context->id==$v): ?>
                    <li class="menu_left_active_index"><span class="ge_border"></span><a href="<?= Url::toRoute([$v.'/'.$k2]) ?>"><?= $v2; ?></a></li>
                    <?php else: ?>
                   <li class=""><a href="<?= Url::toRoute([$v.'/'.$k2]) ?>"><?= $v2; ?></a></li>
                   <?php endif; ?>
                  <?php endforeach; ?>
               </ul>
           <?php endforeach; ?>
        </div>
        
        <div class="main_main pull-right">
            <?= $content ?>
        </div>
        </div>
    </div>
 
    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy;<?= date('Y').' '.GlobalConst::COPYRIGHT; ?> </p>
        <p class="pull-left"></p>
        <p class="pull-right">管理员邮箱：<?=GlobalConst::ADMIN_EMAIL;?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
 
<script>
<?php $this->beginBlock('MY_VIEW_JS_MAIN') ?>
$(document).ready(function(){

    $("img").error(function() {
        $(this).attr("src", "<?= Yii::$app->homeUrl?>/images/basic/basic_header.jpg");
    });

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
})
 
<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_MAIN'],\yii\web\View::POS_END);
?>
</body>
</html>
<?php $this->endPage() ?>

