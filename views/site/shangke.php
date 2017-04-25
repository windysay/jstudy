<?php
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Html;
$this->title = '关于上课';
  
?>
 
<div class="site-benzhan about_basic clearfix">
	<div class="box_l pull-left">
		<ul class="nav_l">
		<li class="news_title <?=$this->context->action->id=='benzhan'?'active':null;?>"><a href="<?= Url::toRoute(['benzhan']) ?>">关于本站</a></li>
		<li class="news_title  <?=$this->context->action->id=='jiangshi'?'active':null;?>"><a href="<?= Url::toRoute(['jiangshi']) ?>">关于讲师</a></li>
		<li class="news_title  <?=$this->context->action->id=='shangke'?'active':null;?>"><a href="<?= Url::toRoute(['shangke']) ?>">关于上课</a></li>
		<li class="news_title  <?=$this->context->action->id=='xuefei'?'active':null;?>"><a href="<?= Url::toRoute(['xuefei']) ?>">关于费用</a></li>	
		</ul>
	</div>
	<div class="box_r pull-right">
      <div class="title"><?=$model['title'];?></div>
      <div class="content">
          <?=$model['content'];?>
      </div>
	</div>
</div>
 